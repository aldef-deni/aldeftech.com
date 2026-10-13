<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per Aldef Store ZIP import: the chunked upload, the extraction,
 * the previewed plan (one entry per product folder) and its outcome.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('store_imports')) {
            return;
        }

        Schema::create('store_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('original_name');
            $table->unsignedBigInteger('size');
            $table->unsignedBigInteger('received')->default(0);
            // uploading → extracting → ready (preview) → importing → done
            // or failed / cancelled / expired
            $table->string('status', 20)->default('uploading');
            $table->unsignedInteger('cursor')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->json('plan')->nullable();
            $table->json('summary')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['status', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_imports');
    }
};
