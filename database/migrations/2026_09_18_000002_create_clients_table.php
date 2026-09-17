<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Client logos shown as social proof on the homepage marquee and on the About
 * grid.
 *
 * Publication reuses is_published, the same flag every other content table
 * carries, so the dashboard keeps one status mechanism and one badge component.
 * What is specific to this table is placement: show_home and show_about let an
 * editor decide where a logo appears without a code change, and is_featured
 * lifts a logo above the rest without resizing anything.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Path produced by the existing inline uploader; a client without a
            // logo has nothing to render, so the public queries skip it.
            $table->string('logo')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('show_home')->default(true);
            $table->boolean('show_about')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Both public queries filter on active + their own placement flag,
            // then order; featured leads that order.
            $table->index(['is_published', 'is_featured', 'sort_order'], 'clients_public_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
