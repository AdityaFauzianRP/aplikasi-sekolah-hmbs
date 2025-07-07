<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * @method bool can(string $permission)
 * @method string getRoleNames()
 * @method array getAllPermissions()
 * @method string getAksesId()
 */

class User extends Authenticatable implements AuthorizableContract
{
    use HasFactory, Notifiable, HasRoles, Authorizable;

    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function pesertaDidik()
    {
        return $this->hasOne(PesertaDidik::class);
    }


    // Removed akses_id and its cast
}
