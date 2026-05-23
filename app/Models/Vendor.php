<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'service_ids' => 'array',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
