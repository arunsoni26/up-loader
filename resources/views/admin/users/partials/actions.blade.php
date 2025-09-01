@if(canDo('users','can_edit'))
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm editUserBtn" data-id="{{ $row->id }}">
            <i class="fa fa-edit"></i>
        </button>
    </div>
@endif

@if(auth()->user()->role->slug == 'superadmin')
    <button class="btn btn-outline-primary btn-sm add-permissions-btn" 
        data-role-id="{{ $row->id }}">
        <i class="bi bi-key"></i> Add Permissions
    </button>
@endif