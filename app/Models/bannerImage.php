<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class bannerImage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'image',
        'description',
        'status',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
