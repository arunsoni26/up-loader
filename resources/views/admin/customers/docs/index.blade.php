@extends('layouts.admin-app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="fa fa-folder-open me-2"></i> Customer Docs — {{ $customer->name }}</h5>
                <small class="opacity-75">Manage uploads by GST year & type</small>
            </div>
            <div class="d-flex gap-2">
                @if (in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
                    <input type="hidden" id="selectedYear">
                    <label class="custom-checkbox" id="verifyBox" style="display: none;">
                        <input type="checkbox" id="yearVerified" value="1">
                        <span class="checkbox-icon">
                            <i class="fas fa-check" id="showVerified" style="display: none;"></i>
                        </span>
                        <span class="ms-2">Verify</span>
                    </label>
                @endif
                <select id="filterYear" class="form-select form-select-sm">
                    <option value="">All GST Years</option>
                    @foreach($years as $y)
                        <option value="{{ $y->id }}" data-verified="{{ $y->verified }}">{{ $y->label }}</option>
                    @endforeach
                </select>
                <select id="filterType" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($docTypes as $docType)
                        <option value="{{ $docType->id }}">{{ $docType->name }}</option>
                    @endforeach
                </select>
              	@if(auth()->user()->hasPermission('customer_docs', 'can_add', auth()->id())) 
                    <button class="btn btn-light btn-sm" id="btnAddGstYear"><i class="fa fa-plus"></i> GST Year</button>
                    <button class="btn btn-success btn-sm" id="btnUploadDocs"><i class="fa fa-upload"></i> Upload Docs</button>
              	@endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="docsTable" class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>GST Year</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>File</th>
                            @if (in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
                                <th>Uploaded By</th>
                            @endif
                            <th>Date</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- ajax --></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Reusable Modal --}}
<div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" id="ajaxModalContent"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const customerId = {{ $customer->id }};
    const tableSelector = '#docsTable';
    let docsTable;
    let retryCount = 1;

    function initDocsTable(retries = retryCount) {
        if ($.fn.DataTable.isDataTable(tableSelector)) {
            $(tableSelector).DataTable().destroy();
        }

        docsTable = $(tableSelector).DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.customers.docs.list', $customer->id) }}",
                data: function(d) {
                    d.gst_year_id = $('#filterYear').val();
                    d.doc_type = $('#filterType').val();
                },
                dataSrc: function(res) {
                    return res.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.error("Document table AJAX error:", xhr.responseText);
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
                        console.warn(`Retrying document table load... (${retryCount - retries + 1})`);
                        setTimeout(() => {
                            initDocsTable(retries - 1);
                        }, 1000);
                    } else {
                        alert("Failed to load document data. Please reload the page.");
                    }
                }
            },
            columns: [
                { data: 'year', defaultContent: '-' },
                { data: 'type', defaultContent: '-' },
                { data: 'desc', defaultContent: '-' },
                { data: 'file', defaultContent: '' },
                @if (in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
                    { data: 'by', defaultContent: '-' },
                @endif
                { data: 'date', defaultContent: '-' },
                { data: 'actions', defaultContent: '', orderable: false, searchable: false }
            ],
            createdRow: function(row) {
                $(row).addClass('zoom-item');
            }
        });
    }

    // Initial table load
    initDocsTable();

    // Reload on filters
    $('#filterYear, #filterType').on('change', function(){
        if (docsTable) {
            docsTable.ajax.reload();
        }

        @if (in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
            let selected = $(this).find(':selected');
            let yearId = selected.val();
            let verified = selected.data('verified');

            $('#selectedYear').val(yearId);

            if (yearId) {
                $('#verifyBox').show();
                if (verified) {
                    $('#yearVerified').prop('checked', true);
                    $('#showVerified').show();
                } else {
                    $('#yearVerified').prop('checked', false);
                    $('#showVerified').hide();
                }
            } else {
                $('#verifyBox').hide();
                $('#yearVerified').prop('checked', false);
                $('#showVerified').hide();
            }
        @endif
    });

    // When verify checkbox is toggled
    @if (in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
        $('#yearVerified').on('change', function () {
            let yearId = $('#selectedYear').val();
            let customerId = "{{ $customer->id }}"; // pass customer id from blade

            if (!yearId) return;

            if(!confirm('Are you sure? You want to change this')) {
                $('#yearVerified').prop('checked', !$('#yearVerified').prop('checked'));
                return ;
            };
            $.ajax({
                url: "{{ route('admin.customers.gstYears.verify', ':customerId') }}".replace(':customerId', customerId),
                type: 'POST',
                data: {
                    gst_year_id: yearId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.success) {
                        if (res.verified) {
                            $('#showVerified').show();
                            // also update <option> attribute so it's synced
                            $(`#filterYear option[value="${yearId}"]`).data('verified', 1);
                        } else {
                            $('#showVerified').hide();
                            $(`#filterYear option[value="${yearId}"]`).data('verified', 0);
                        }
                    } else {
                        alert('Update failed!');
                        // revert checkbox
                        $('#yearVerified').prop('checked', !$('#yearVerified').prop('checked'));
                    }
                },
                error: function () {
                    alert('Something went wrong.');
                    $('#yearVerified').prop('checked', !$('#yearVerified').prop('checked'));
                }
            });
        });
    @endif

    // Keep your existing event handlers intact below...

    $('#btnUploadDocs').on('click', function(){
        $.post("{{ route('admin.customers.docs.form', $customer->id) }}",
            {_token: "{{ csrf_token() }}"},
            function(html){
                $('#ajaxModalContent').html(html);
                $('#ajaxModal').modal('show');
            }
        );
    });

    $('#btnAddGstYear').on('click', function(){
        const lbl = prompt('Enter GST Year (e.g., 2024-2025)');
        if(!lbl) return;
        $.post("{{ route('admin.gst_years.save') }}", {
            _token: "{{ csrf_token() }}",
            label: lbl
        }, function(res){
            if(res.success){
                $('#filterYear').append(`<option value="${res.data.id}">${res.data.label}</option>`);
                $('#filterYear').val(res.data.id).trigger('change');
                if($('#docs_gst_year').length){
                    $('#docs_gst_year').prepend(`<option value="${res.data.id}">${res.data.label}</option>`)
                                        .val(res.data.id);
                }
            }
        });
    });

    $(document).on('click', '.deleteDoc', function(){
        if(!confirm('Delete this document?')) return;
        const id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.customers.docs.delete', [$customer->id, 0]) }}".replace('/0', '/' + id),
            type: 'POST',
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){
                if(res.success && docsTable) docsTable.ajax.reload();
            }
        });
    });

})();
</script>
@endpush

@push('custom-style')
<style>
    .custom-checkbox {
      display: inline-flex;
      align-items: center;
      cursor: pointer
    }

    .custom-checkbox input[type="checkbox"] {
      display: none;
    }

    .checkbox-icon {
      width: 24px;
      height: 24px;
      border: 2px solid #ccc;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;
    }

    .custom-checkbox input[type="checkbox"]:checked + .checkbox-icon {
      background-color: #0d6efd;
      border-color: #0d6efd;
      color: white;
    }
</style>
@endpush