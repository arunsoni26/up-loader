<div class="btn-group">
    <a href="{{ route('admin.customers.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <button type="button" class="btn btn-info btn-sm viewCustomer" data-id="{{ $row->id }}">
        <i class="fa fa-eye"></i>
    </button>
</div>