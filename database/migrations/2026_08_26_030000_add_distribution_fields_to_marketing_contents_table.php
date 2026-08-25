<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_contents', function (Blueprint $table) {
            $table->json('platform_posts')->nullable()->after('seo_keywords');
            $table->json('distribution_checklist')->nullable()->after('platform_posts');

            $table->foreignId('published_blog_post_id')
                ->nullable()
                ->after('published_at')
                ->constrained('blog_posts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('marketing_contents', function (Blueprint $table) {
            $table->dropForeign(['published_blog_post_id']);
            $table->dropColumn([
                'platform_posts',
                'distribution_checklist',
                'published_blog_post_id',
            ]);
        });
    }
};
