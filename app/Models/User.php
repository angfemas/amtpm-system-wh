<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'nrp',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function maintenanceLogsAsOperator(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'operator_id');
    }

    public function maintenanceLogsAsLeader(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'leader_id');
    }

    public function redWhiteTagsCreated(): HasMany
    {
        return $this->hasMany(RedWhiteTag::class, 'created_by');
    }

    public function redWhiteTagsResolved(): HasMany
    {
        return $this->hasMany(RedWhiteTag::class, 'resolved_by');
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nrp ? $this->nrp : $this->name;
    }
}
