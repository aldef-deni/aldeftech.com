<?php

namespace App\Services;

use App\Models\SiteSetting;

/**
 * The WhatsApp number Aldef Store sends visitors to.
 *
 * The catalogue's own number wins; otherwise the business number saved under
 * Pengaturan > WhatsApp. Only numbers stored in the admin count: the .env
 * fallback that the rest of the site uses can hold the .env.example
 * placeholder, and a live link to a made-up number is worse than none.
 */
class StoreWhatsApp
{
    public const SETTING_KEY = 'store_whatsapp_number';

    /** Placeholder numbers that must never become a live wa.me link. */
    private const PLACEHOLDERS = ['6281234567890', '628123456789', '6280000000000', '620000000000'];

    /** Digits-only international number, or null when none is configured. */
    public static function number(): ?string
    {
        foreach ([SiteSetting::get(self::SETTING_KEY), SiteSetting::get('whatsapp_number')] as $candidate) {
            $number = static::normalize((string) $candidate);

            if ($number !== null) {
                return $number;
            }
        }

        return null;
    }

    /** Where the number in use comes from, for the admin settings screen. */
    public static function source(): ?string
    {
        if (static::normalize((string) SiteSetting::get(self::SETTING_KEY)) !== null) {
            return 'store';
        }

        return static::normalize((string) SiteSetting::get('whatsapp_number')) !== null ? 'business' : null;
    }

    /**
     * Turn what an admin typed into an international number: "0812-8968-609",
     * "+62 812 8968 609" and "812 8968 609" all become 628128968609.
     * Returns null for anything that cannot be a real number.
     */
    public static function normalize(string $input): ?string
    {
        $digits = preg_replace('/\D/', '', $input);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        if (! preg_match('/^[1-9][0-9]{9,14}$/', $digits) || in_array($digits, self::PLACEHOLDERS, true)) {
            return null;
        }

        return $digits;
    }
}
