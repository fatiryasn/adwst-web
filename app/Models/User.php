<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    // UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'users';

    //fillable
    protected $fillable = [
        'email',
        'password',
        'full_name',
        'phone_number',
        'role',
    ];
    //hidden
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // attribute casting
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // RELATIONS: verified tickets
    public function verifiedTickets()
    {
        return $this->hasMany(Ticket::class, 'payment_verified_by');
    }

    //HELPERS
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
}
