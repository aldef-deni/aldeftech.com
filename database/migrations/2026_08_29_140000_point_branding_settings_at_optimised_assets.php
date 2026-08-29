<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Logo and Favicon settings were saved but never rendered, so their values
 * were never exercised. Now that the layout reads them, the seeded defaults
 * would quietly serve the pre-WebP assets again — a 576 KB logo on every page
 * and a 1.3 MB favicon — undoing the image work.
 */
return new class extends Migration
{
    public function up(): void
    {
        // The bundled logo is now WebP: same artwork, 45 KB instead of 576 KB.
        DB::table('site_settings')
            ->where('key', 'site_logo')
            ->whereIn('value', ['images/logo.png', 'images/logo-landscape-trans.png', ''])
            ->update(['value' => 'images/logo.webp', 'updated_at' => now()]);

        // Emptied rather than repointed: blank falls through to the generated
        // 16/32/180px icon set, which one uploaded file cannot cover.
        DB::table('site_settings')
            ->where('key', 'site_favicon')
            ->whereIn('value', ['images/logo-square.png', 'images/favicon.png'])
            ->update(['value' => '', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'site_logo')
            ->where('value', 'images/logo.webp')
            ->update(['value' => 'images/logo.png', 'updated_at' => now()]);

        DB::table('site_settings')
            ->where('key', 'site_favicon')
            ->where('value', '')
            ->update(['value' => 'images/logo-square.png', 'updated_at' => now()]);
    }
};
