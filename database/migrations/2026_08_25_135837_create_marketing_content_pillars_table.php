<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_content_pillars', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->text('objectives')->nullable();

            $table->string('content_types')->nullable();
            $table->string('funnel_stage')->nullable();

            $table->unsignedInteger('default_priority')->default(0);
            $table->unsignedInteger('default_frequency')->default(1);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['is_active', 'default_priority']);
            $table->index('funnel_stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_content_pillars');
    }
};
