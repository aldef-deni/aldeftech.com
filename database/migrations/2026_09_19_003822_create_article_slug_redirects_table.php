<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->string('old_slug', 255);
            $table->timestamps();

            $table->unique(['article_id', 'old_slug']);
            $table->index('old_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_slug_redirects');
    }
};
