<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class CeoProfile extends Model
{
    use HasTranslations;

    /** Leadership roles the homepage renders, in order of appearance. */
    public const ROLE_CEO = 'ceo';
    public const ROLE_COMMISSIONER = 'komisaris';

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['position', 'short_bio', 'full_bio', 'skills', 'experience'];

    protected $fillable = [
        'role', 'name', 'position', 'profile_photo', 'short_bio', 'full_bio',
        'skills', 'experience', 'linkedin', 'github', 'instagram',
        'email', 'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * The row for a role, created with the minimum identity data the first time
     * an administrator opens the profile page. Copy is never invented here: the
     * editor fills in bio, photo and skills, exactly as for the CEO.
     */
    public static function forRole(string $role): self
    {
        $defaults = $role === self::ROLE_COMMISSIONER
            ? ['name' => 'Muhammad Ramadhan', 'position' => 'Komisaris']
            : ['name' => 'Deni Afrizal', 'position' => 'CEO & System/Application Developer'];

        return static::firstOrCreate(['role' => $role], $defaults + ['is_active' => true]);
    }
}
