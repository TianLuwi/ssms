<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getProfilePictureUrlAttribute(): string
    {
        $pic = $this->profile_picture ?? 'default.png';
        if ($pic === 'default.png') {
            return asset('images/default-avatar.png');
        }
        return asset('uploads/profiles/' . $pic);
    }
}
