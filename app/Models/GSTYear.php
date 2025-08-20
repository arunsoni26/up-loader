<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GSTYear extends Model
{
    use SoftDeletes;

    protected $table = 'gst_years';
    
    protected $fillable = [
        'label'
    ];
}
