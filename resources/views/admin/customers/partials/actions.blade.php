<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm editCustomerBtn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Customer" data-id="{{ $row->id }}">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" class="btn btn-info btn-sm viewCustomer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Customer" data-id="{{ $row->id }}">
        <i class="fa fa-eye"></i>
    </button>
    
    @if(canDo('customer_docs','can_view'))
        <a href="{{ url('admin/customers/'.$row->id.'/docs' ) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Documents" class="btn btn-warning btn-sm">
            <i class="fa fa-file-arrow-up"></i>
        </a>
    @endif
    
    @if(canDo('ledgers','can_view'))
        <a href="{{ url('admin/customers/'.$row->id.'/ledger' ) }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ledger" class="btn btn-success btn-sm">
            <i class="fa fa-book"></i>
        </a>
    @endif
</div>



<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>