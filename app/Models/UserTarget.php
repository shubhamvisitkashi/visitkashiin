<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'target_margin',
        'achieved_margin',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'target_margin' => 'decimal:2',
        'achieved_margin' => 'decimal:2',
    ];

    /**
     * Get the user this target belongs to
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Admin\Admin::class, 'user_id');
    }

    /**
     * Get the admin who created this target
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin\Admin::class, 'created_by');
    }

    /**
     * Get achievement percentage
     */
    public function getAchievementPercentageAttribute()
    {
        if ($this->target_margin <= 0) {
            return 0;
        }
        return round(($this->achieved_margin / $this->target_margin) * 100, 2);
    }

    /**
     * Get remaining margin to achieve target
     */
    public function getRemainingMarginAttribute()
    {
        return max(0, $this->target_margin - $this->achieved_margin);
    }

    /**
     * Check if target is achieved
     */
    public function getIsAchievedAttribute()
    {
        return $this->achieved_margin >= $this->target_margin;
    }

    /**
     * Get month name
     */
    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    /**
     * Scope for current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where('month', now()->month)
                    ->where('year', now()->year);
    }

    /**
     * Scope for last month
     */
    public function scopeLastMonth($query)
    {
        $lastMonth = now()->subMonth();
        return $query->where('month', $lastMonth->month)
                    ->where('year', $lastMonth->year);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific month and year
     */
    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('month', $month)
                    ->where('year', $year);
    }
}
