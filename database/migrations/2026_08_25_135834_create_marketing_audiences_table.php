<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_audiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            $table->string('decision_maker')->nullable();

            $table->text('description')->nullable();
            $table->text('goals')->nullable();
            $table->text('needs')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);

            $table->timestamps();

            $table->index(['is_active', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_audiences');
    }
};
