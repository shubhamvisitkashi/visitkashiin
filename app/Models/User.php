<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'pending_email',
        'phone',
        'pending_phone',
        'password',
        'google_id',
        'profile_photo',
        'status',
        'login_method',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo && file_exists(public_path('uploads/customer-photos/' . $this->profile_photo))) {
            return asset('uploads/customer-photos/' . $this->profile_photo);
        }
        return '';
    }

    /**
     * Activity Log Configuration — never logs password/OTP/OAuth values,
     * only identity fields that are safe to show in the admin audit trail.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'status', 'login_method'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('customer')
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Customer account created',
                'updated' => 'Customer account updated',
                'deleted' => 'Customer account deleted',
                default => "Customer {$eventName}",
            });
    }
}
