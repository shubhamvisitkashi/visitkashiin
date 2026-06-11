<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'avatar',
    ];

    // Encrypt on write
    public function setPlainPasswordAttribute(?string $value): void
    {
        $this->attributes['plain_password'] = $value ? Crypt::encryptString($value) : null;
    }

    // Decrypt on read; fall back to raw value for old unencrypted records
    public function getPlainPasswordAttribute(?string $value): ?string
    {
        if ($value === null) return null;
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException) {
            return $value; // legacy plaintext row — still readable until re-saved
        }
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('uploads/avatars/'.$this->avatar))) {
            return asset('uploads/avatars/'.$this->avatar);
        }
        return '';
    }

}
