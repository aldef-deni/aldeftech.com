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

    /** Every role, CEO first: the order the site lists the leadership team in. */
    public const ROLES = [self::ROLE_CEO, self::ROLE_COMMISSIONER];

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
     * The identity a profile starts from when no row has been stored yet. Only
     * the public facts belong here — name and role. Bio, photo, skills and links
     * are never invented: the editor fills those in, exactly as for the CEO.
     */
    public static function defaultsFor(string $role): array
    {
        return $role === self::ROLE_COMMISSIONER
            ? ['name' => 'Muhammad Ramadhan', 'position' => 'Komisaris']
            : ['name' => 'Deni Afrizal', 'position' => 'CEO & System/Application Developer'];
    }

    /**
     * The row for a role, or an unsaved model holding that role's defaults.
     *
     * Reading a profile must never write: opening the admin page, or submitting a
     * form that fails validation, has to leave the table exactly as it was. The
     * role is always part of the lookup, so the CEO row and the commissioner row
     * can never be confused for one another.
     */
    public static function forRole(string $role): self
    {
        return static::role($role)->first()
            ?? new static(static::defaultsFor($role) + ['role' => $role, 'is_active' => true]);
    }
}
