<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a single JSON column per content table to hold non-default-locale copy.
 *
 * Shape: {"en": {"title": "...", "short_description": "..."}}
 *
 * A JSON column is used rather than one *_en column per field so that adding a
 * third language later needs no further migration, and so the base (Indonesian)
 * values stay exactly where they are — nothing existing is moved or rewritten.
 */
return new class extends Migration
{
    /**
     * Tables that carry translatable copy. Keep in step with the $translatable
     * arrays on the models.
     */
    private array $tables = [
        'services',
        'solutions',
        'process_steps',
        'portfolios',
        'testimonials',
        'faqs',
        'blog_posts',
        'ceo_profiles',
        'site_settings',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'translations')) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) {
                $t->json('translations')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'translations')) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('translations');
            });
        }
    }
};
