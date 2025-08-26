<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDocument extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id','gst_year_id','doc_type','description','file_path','uploaded_by'
    ];

    public const TYPES = [
        'itr'          => 'Income Tax Return',
        'computation'  => 'Computations',
        'balance_sheet'=> 'Balance Sheet',
        'audit'        => 'Audit Files',
        'pl'           => 'PL A/c',
        'other'        => 'Others',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function gstYear(){
        return $this->belongsTo(GSTYear::class);
    }
    public function uploader(){
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function docType(){
        return $this->belongsTo(DocType::class, 'doc_type');
    }
}
