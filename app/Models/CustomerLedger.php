<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'type', 'amount', 'description', 'date', 'created_by', 'updated_by'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
