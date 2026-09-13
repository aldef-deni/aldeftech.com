<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_growth_runs', function (Blueprint $table) {
            $table->id();
            $table->string('run_key', 100)->unique();
            $table->string('task', 80)->index();
            $table->string('status', 20)->default('running');
            $table->json('result')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
        Schema::create('seo_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint', 64)->unique();
            $table->string('category', 100);
            $table->string('cluster', 150);
            $table->string('topic');
            $table->string('keyword', 150);
            $table->text('reason');
            $table->unsignedTinyInteger('priority')->default(3);
            $table->string('status', 20)->default('new')->index();
            $table->foreignId('pillar_blog_post_id')->nullable()->constrained('blog_posts')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('seo_page_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->unique()->constrained('blog_posts')->cascadeOnDelete();
            $table->string('page_url', 1000);
            $table->string('target_keyword', 150)->nullable();
            $table->string('optimization_status', 30)->default('review_needed');
            $table->json('recommendations')->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamp('next_review_at')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('backlink_prospects', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('url', 1000);
            $table->string('website_name');
            $table->string('category', 80);
            $table->unsignedTinyInteger('relevance_score');
            $table->decimal('authority_score', 8, 2)->nullable();
            $table->decimal('traffic_score', 12, 2)->nullable();
            $table->string('metrics_provider', 80)->nullable();
            $table->string('spam_risk', 30)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_url', 1000)->nullable();
            $table->foreignId('target_blog_post_id')->nullable()->constrained('blog_posts')->nullOnDelete();
            $table->string('suggested_anchor')->nullable();
            $table->text('suggested_pitch')->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->text('notes')->nullable();
            $table->json('evidence');
            $table->timestamp('verified_at');
            $table->timestamps();
        });
        Schema::create('seo_outreach_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlink_prospect_id')->unique()->constrained('backlink_prospects')->cascadeOnDelete();
            $table->string('subject');
            $table->text('body');
            $table->json('guest_post')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_outreach_drafts');
        Schema::dropIfExists('backlink_prospects');
        Schema::dropIfExists('seo_page_reviews');
        Schema::dropIfExists('seo_opportunities');
        Schema::dropIfExists('seo_growth_runs');
    }
};
