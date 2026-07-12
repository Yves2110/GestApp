<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'service_id',
        'firstname',
        'lastname',
        'email',
        'number',
        'password',
        'is_active',
        'must_reset_password',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'must_reset_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function serviceRelation()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Vérifie si l'utilisateur possède une permission via son rôle.
     * Le SuperAdmin (role_id = 1) possède toutes les permissions.
     */
    public function hasPermission(string $permissionName): bool
    {
        if ((int) $this->role_id === 1) {
            return true;
        }

        $role = Role::find($this->role_id);

        return $role ? $role->hasPermission($permissionName) : false;
    }

    public function isSuperAdmin(): bool
    {
        return (int) $this->role_id === 1;
    }
        /**

     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */

    protected function type(): Attribute

    {
        return new Attribute(
            get: fn ($value) =>  ["SuperAdmin","president", "admin", "services"][$value],

        );

    }
}
