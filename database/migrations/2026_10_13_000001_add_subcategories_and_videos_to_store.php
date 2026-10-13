<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aldef Store: categories gain one level of subcategories, and a product's
 * gallery can hold videos next to photos. Only adds columns; existing rows
 * keep working (no parent, every gallery item an image).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('store_categories', 'parent_id')) {
            Schema::table('store_categories', function (Blueprint $table) {
                // Deleting a parent turns its subcategories into top-level
                // categories instead of deleting them.
                $table->foreignId('parent_id')->nullable()->after('id')
                    ->constrained('store_categories')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('store_product_images', 'type')) {
            Schema::table('store_product_images', function (Blueprint $table) {
                $table->string('type', 10)->default('image')->after('store_product_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('store_product_images', 'type')) {
            Schema::table('store_product_images', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }

        if (Schema::hasColumn('store_categories', 'parent_id')) {
            Schema::table('store_categories', function (Blueprint $table) {
                $table->dropConstrainedForeignId('parent_id');
            });
        }
    }
};
