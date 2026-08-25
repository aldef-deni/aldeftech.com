<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_content_ideas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketing_audience_id')
                ->nullable()
                ->constrained('marketing_audiences')
                ->nullOnDelete();

            $table->foreignId('marketing_pain_point_id')
                ->nullable()
                ->constrained('marketing_pain_points')
                ->nullOnDelete();

            $table->foreignId('marketing_keyword_id')
                ->nullable()
                ->constrained('marketing_keywords')
                ->nullOnDelete();

            $table->foreignId('marketing_content_pillar_id')
                ->nullable()
                ->constrained('marketing_content_pillars')
                ->nullOnDelete();

            $table->string('title');
            $table->text('hook')->nullable();
            $table->text('brief')->nullable();

            $table->string('content_type')->default('article');
            $table->string('funnel_stage')->nullable();

            $table->string('status')->default('idea');

            $table->unsignedInteger('priority')->default(0);

            $table->json('platforms')->nullable();

            $table->timestamp('scheduled_at')->nullable();

            $table->text('cta')->nullable();

            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_content_ideas');
    }
};
