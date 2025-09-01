<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGstYearVerified extends Model
{
    protected $fillable = [
        'customer_id',
        'gst_year_id',
        'is_verify',
        'status'
    ];
}
