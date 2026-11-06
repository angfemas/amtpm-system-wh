<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
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
        'profile_photo_path',
        'google_id',
        'google_avatar',
        'section',
        'status',
        'last_login_at',
        'notification_enabled',
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
            'last_login_at' => 'datetime',
            'notification_enabled' => 'boolean',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return 'nrp';
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

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        if ($this->google_avatar) {
            return $this->google_avatar;
        }

        // Use a placeholder from UI Avatars instead of local file
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff&size=128';
    }

    public function getAccountTypeAttribute(): string
    {
        return $this->roles->first()?->name ?? 'user';
    }

    public function getMemberSinceAttribute(): string
    {
        return $this->created_at?->format('d M Y') ?? '-';
    }

    public function getLastLoginAttribute(): string
    {
        return $this->last_login_at?->diffForHumans() ?? '-';
    }
}
