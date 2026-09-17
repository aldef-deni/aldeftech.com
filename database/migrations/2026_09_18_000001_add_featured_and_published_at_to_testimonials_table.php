<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A testimonial can be highlighted above the others, and it can carry the date
 * it went live. Publication itself keeps using is_published, exactly like
 * portfolios, so "status" stays derived instead of duplicated in two columns.
 *
 * Both columns are additive: existing rows keep their copy, and an existing
 * is_published row is treated as published once published_at <= now().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('rating');
            $table->timestamp('published_at')->nullable()->after('is_published');

            // The public query filters on both, then orders by the featured flag.
            $table->index(['is_published', 'published_at'], 'testimonials_publication_index');
        });

        // A row that was already published predates published_at, so it would
        // silently disappear from the site. Dating it from created_at keeps the
        // reviews that are live today live.
        DB::table('testimonials')
            ->where('is_published', true)
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('testimonials_publication_index');
            $table->dropColumn(['is_featured', 'published_at']);
        });
    }
};
