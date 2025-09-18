<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'donor_type',
        'is_anonymous',
        'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_anonymous' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function getAllRoles()
    {
        $column = \DB::select('SHOW COLUMNS FROM users WHERE Field = "role"')[0];

        preg_match("/^enum\((.*)\)$/", $column->Type, $matches);

        $roles = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $roles;
    }

    // Relationships
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function pledge()
    {
        return $this->hasOne(Pledge::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeMonthlyDonors($query)
    {
        return $query->where('donor_type', 'monthly')->where('is_active', true);
    }

    public function scopeActiveDonors($query)
    {
        return $query->where('is_active', true);
    }
}
