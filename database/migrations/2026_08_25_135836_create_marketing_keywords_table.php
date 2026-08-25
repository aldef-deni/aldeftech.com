<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_keywords', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketing_audience_id')
                ->nullable()
                ->constrained('marketing_audiences')
                ->nullOnDelete();

            $table->string('keyword');
            $table->string('slug')->nullable();

            $table->string('search_intent')->nullable();
            $table->string('keyword_type')->nullable();

            $table->unsignedInteger('search_volume')->nullable();
            $table->decimal('keyword_difficulty', 8, 2)->nullable();

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);

            $table->timestamps();

            $table->index(['keyword', 'is_active']);
            $table->index(['search_intent', 'priority']);
            $table->index(['marketing_audience_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_keywords');
    }
};
