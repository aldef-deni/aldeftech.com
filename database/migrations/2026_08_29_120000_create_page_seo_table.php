<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_seo', function (Blueprint $table) {
            $table->id();

            // Plain route name without a language prefix: 'services', not 'en.services'.
            $table->string('route_name', 100);
            $table->string('locale', 5);

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_image', 500)->nullable();
            $table->boolean('noindex')->default(false);

            $table->timestamps();

            $table->unique(['route_name', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
    }
};
