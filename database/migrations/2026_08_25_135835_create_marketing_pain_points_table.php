<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_pain_points', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marketing_audience_id')
                ->constrained('marketing_audiences')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('severity')->default('medium');
            $table->text('business_impact')->nullable();
            $table->text('desired_solution')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);

            $table->timestamps();

            $table->index(['marketing_audience_id', 'is_active']);
            $table->index(['severity', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_pain_points');
    }
};
