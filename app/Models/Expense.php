<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'type',
        'category',
        'staff_id',
        'party_name',
        'amount',
        'expense_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public const TYPES = [
        'stay'    => 'Stay',
        'cab'     => 'Cab',
        'boat'    => 'Boat',
        'tour'    => 'Tour',
        'general' => 'General',
    ];

    public const CATEGORIES = [
        'stay'    => ['Hotel Rent', 'Staff Salary', 'Housekeeping', 'Maintenance', 'Utilities', 'Other'],
        'cab'     => ['Cab Fuel', 'Cab Service', 'Driver Salary', 'Vehicle Maintenance', 'Other'],
        'boat'    => ['Boat Fuel', 'Boat Maintenance', 'Boatman Salary', 'Other'],
        'tour'    => ['Guide Fee', 'Permits', 'Other'],
        'general' => ['Staff Salary', 'Office Rent', 'Utilities', 'Marketing', 'Other'],
    ];

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function staff()
    {
        return $this->belongsTo(Admin::class, 'staff_id');
    }

    public function isSalaryCategory(): bool
    {
        return str_contains(strtolower($this->category), 'salary');
    }

    public function isPartyNameCategory(): bool
    {
        return str_contains(strtolower($this->category), 'hotel');
    }
}
