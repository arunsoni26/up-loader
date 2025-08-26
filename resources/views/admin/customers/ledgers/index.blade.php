{{-- resources/views/admin/ledgers/index.blade.php --}}
@extends('layouts.admin-app')
@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header text-white d-flex justify-content-between align-items-center rounded-top-4 p-3">
            <h4 class="mb-0 fw-bold">
                Ledger for {{ $customer->name }}
            </h4>
            
            @if(canDo('ledgers','can_add'))
                <button class="btn btn-primary" id="addLedgerBtn">
                    <i class="fa fa-plus"></i> Add Ledger Entry
                </button>
            @endif
        </div>
        

        <div class="card-body">
            <div class="mb-3">
                <h4>Outstanding: 
                    @if($outstanding > 0)
                        <span class="text-success fw-bold">{{ number_format($outstanding, 2) }}</span>
                    @elseif($outstanding < 0)
                        <span class="text-danger fw-bold">{{ number_format($outstanding, 2) }}</span>
                    @else
                        <span class="text-muted fw-bold">{{ number_format($outstanding, 2) }}</span>
                    @endif
                </h4>
            </div>
    
    
            <table class="table table-bordered table-striped" id="ledgerTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th class="bg-light-danger">Debit</th>
                        <th class="bg-light-success">Credit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

    @if(canDo('ledgers','can_add'))
        {{-- Modal --}}
        <div class="modal fade" id="ledgerModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="ledgerForm">
                        @csrf
                        <input type="hidden" name="id" id="ledgerId">

                        <div class="modal-header">
                            <h5 class="modal-title">Ledger Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Type</label>
                                <select class="form-control" name="type" id="ledgerType" required>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Amount</label>
                                <input type="number" class="form-control" name="amount" id="ledgerAmount" required>
                            </div>

                            <div class="mb-3">
                                <label>Date</label>
                                <input type="datetime-local" class="form-control" name="date" id="ledgerDate" required>
                            </div>

                            <div class="mb-3">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="ledgerDescription"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
$(function(){
    let retryCount = 1;
    let table;

    function initLedgerTable(retries = retryCount) {
        if ($.fn.DataTable.isDataTable('#ledgerTable')) {
            $('#ledgerTable').DataTable().destroy();
        }

        let url = "{{ route('admin.customers.ledger.list', $customer) }}";
        // url = url.replace(':id', customerId);

        table = $('#ledgerTable').DataTable({
            processing: true,
            serverSide: false, // no package, so we use client-side with JSON
            ajax: {
                url: url,
                error: function (xhr, error, thrown) {
                    console.error("DataTables AJAX error:", xhr.responseText);
                    let isServerError = false;

                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (json.message && json.message === "Server Error") {
                            isServerError = true;
                        }
                    } catch (e) {
                        isServerError = xhr.status === 500;
                    }

                    if (retries > 0 && isServerError) {
                        console.warn(`Retrying ledger table load... (${retryCount - retries + 1})`);
                        setTimeout(() => {
                            initLedgerTable(retries - 1);
                        }, 1000);
                    } else {
                        alert("Failed to load ledger data. Please reload the page.");
                    }
                }
            },
            columns: [
                { data: 'date' },
                { data: 'description' },
                { data: 'debit', className: 'bg-light-red text-end' },
                { data: 'credit', className: 'bg-light-green text-end' },
                { data: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).addClass('zoom-item');
            }
        });
    }

    initLedgerTable();
    
    @if(canDo('ledgers','can_add'))
        // Outstanding
        function updateOutstanding(){
            $.get('{{ route("admin.customers.ledger.list", $customer->id) }}', function(res){
                let debit = res.data.filter(r=>r.type==='debit').reduce((a,b)=>a+parseFloat(b.amount),0);
                let credit = res.data.filter(r=>r.type==='credit').reduce((a,b)=>a+parseFloat(b.amount),0);
                let balance = credit - debit;
                let color = balance > 0 ? 'text-success' : balance < 0 ? 'text-danger' : 'text-secondary';
                $('#outstandingAmount').text(balance).attr('class', color);
            });
        }
        updateOutstanding();
        
        // Add
        $('#addLedgerBtn').click(function(){
            $('#ledgerForm')[0].reset();
            $('#ledgerId').val('');
            $('#ledgerModal').modal('show');
        });
        
        // Save
        $('#ledgerForm').submit(function(e){
            e.preventDefault();
            $.post('{{ route("admin.customers.ledger.save", $customer->id) }}', $(this).serialize(), function(){
                $('#ledgerModal').modal('hide');
                table.ajax.reload();
                updateOutstanding();
                window.location.reload();
            });
        });

        // Delete Ledger
        $(document).on('click', '.delete-ledger', function(){
            if(confirm('Delete this entry?')){
                $.ajax({
                    url: '{{ url("admin/customers/".$customer->id."/ledger/delete") }}/' + $(this).data('id'),
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(){
                        table.ajax.reload();
                        updateOutstanding();
                        window.location.reload();
                    }
                });
            }
        });

        // Edit Ledger
        $(document).on('click', '.edit-ledger', function(){
            let id = $(this).data('id');
            let date = $(this).data('date');
            let description = $(this).data('description');
            let amount = $(this).data('amount');
            let type = $(this).data('ledger-type');

            $('#ledgerId').val(id);
            $('#ledgerDate').val(date);
            $('#ledgerDescription').val(description);
            $('#ledgerAmount').val(amount);
            $('#ledgerType').val(type);

            $('#ledgerModal').modal('show');
        });
    @endif
});
</script>
@endpush
