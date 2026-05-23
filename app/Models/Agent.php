<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'admin_id',
        'name',
        'contact_number',
        'alt_contact_number',
        'country',
        'state',
        'city',
        'address',
        'service_ids',
        'is_active'
    ];

    protected $casts = [
        'service_ids' => 'array',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
