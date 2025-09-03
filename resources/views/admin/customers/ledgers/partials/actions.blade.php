<div class="btn-group" role="group">
    @if(canDo('ledgers','can_edit'))
        <!-- Edit Button -->
        <button 
            type="button" 
            class="btn btn-sm btn-primary edit-ledger" 
            data-id="{{ $row->id }}"
            data-date="{{ $row->date }}"
            data-description="{{ $row->description }}"
            data-amount="{{ $row->amount }}"
            data-ledger-type="{{ $row->type }}"
        >
            <i class="fa fa-edit"></i>
        </button>
    @endif

    @if(canDo('ledgers','can_edit'))
        <!-- Delete Button -->
        <button 
            type="button" 
            class="btn btn-sm btn-danger delete-ledger" 
            data-id="{{ $row->id }}"
        >
            <i class="fa fa-trash"></i>
        </button>
    @endif
</div>