<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketing_content_idea_id')
                ->nullable()
                ->constrained('marketing_content_ideas')
                ->nullOnDelete();

            $table->foreignId('marketing_campaign_id')
                ->nullable()
                ->constrained('marketing_campaigns')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->nullable();

            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();

            $table->string('content_type')->default('article');
            $table->string('funnel_stage')->nullable();

            $table->string('status')->default('draft');

            $table->string('ai_model')->nullable();
            $table->string('ai_prompt_version')->nullable();

            $table->timestamp('generated_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'content_type']);
            $table->index('generated_at');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_contents');
    }
};
