<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm editCustomerBtn" data-id="{{ $row->id }}">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" class="btn btn-info btn-sm viewCustomer" data-id="{{ $row->id }}">
        <i class="fa fa-eye"></i>
    </button>
    
    @if(canDo('customer_docs','can_view'))
        <a href="{{ url('admin/customers/'.$row->id.'/docs' ) }}" class="btn btn-warning btn-sm">
            <i class="fa fa-file-arrow-up"></i>
        </a>
    @endif
    
    @if(canDo('ledgers','can_view'))
        <a href="{{ url('admin/customers/'.$row->id.'/ledger' ) }}" class="btn btn-success btn-sm">
            <i class="fa fa-file-arrow-up"></i>
        </a>
    @endif
</div>