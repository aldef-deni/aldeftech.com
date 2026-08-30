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
        'avatar',
        'password',
    ];

    /**
     * Resolved avatar URL, or null when the user has not uploaded one.
     *
     * Callers fall back to initials rather than to a stock silhouette: a real
     * person's initials read as identity, a generic placeholder reads as a
     * broken image.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? media_url($this->avatar) : null;
    }

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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()->whereHas('permissions', function ($q) use ($permissionName) {
            $q->where('name', $permissionName);
        })->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isEditor(): bool
    {
        return $this->hasAnyRole(['super-admin', 'editor']);
    }

    public function isSalesManager(): bool
    {
        return $this->hasAnyRole(['super-admin', 'sales-manager']);
    }
}
