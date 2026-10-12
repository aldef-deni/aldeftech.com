<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aldef Store: a shoe catalogue without prices. Visitors pick a model, size,
 * colour and quantity, then ask for the price over WhatsApp.
 *
 * Only creates new tables; nothing existing is touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('store_categories')) {
            Schema::create('store_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('store_products')) {
            Schema::create('store_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_category_id')->nullable()
                    ->constrained('store_categories')->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                // ATS + DDMMYYYY + 5-digit running number; generated, never typed.
                $table->string('sku', 32)->unique();
                $table->unsignedInteger('sku_sequence')->unique();
                $table->string('brand')->nullable();
                $table->longText('description')->nullable();
                $table->string('material')->nullable();
                $table->string('size_system', 20)->default('EU');
                $table->text('size_guide')->nullable();
                $table->string('status', 20)->default('draft');
                $table->boolean('is_featured')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamp('published_at')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
                // Soft deletes keep a removed product's SKU number and slug
                // reserved, so neither is ever handed to a different shoe.
                $table->softDeletes();

                $table->index(['status', 'sort_order']);
            });
        }

        if (! Schema::hasTable('store_product_images')) {
            Schema::create('store_product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_product_id')->constrained('store_products')->cascadeOnDelete();
                $table->string('path', 500);
                $table->string('alt')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('store_product_variants')) {
            Schema::create('store_product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_product_id')->constrained('store_products')->cascadeOnDelete();
                // Empty string, not NULL, means "this product has no such
                // option": a unique index treats every NULL as distinct.
                $table->string('size', 20)->default('');
                $table->string('color', 60)->default('');
                $table->boolean('is_available')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['store_product_id', 'size', 'color']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('store_product_variants');
        Schema::dropIfExists('store_product_images');
        Schema::dropIfExists('store_products');
        Schema::dropIfExists('store_categories');
    }
};
