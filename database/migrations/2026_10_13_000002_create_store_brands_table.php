<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Aldef Store brands become their own records instead of free text on each
 * product, so they can be managed, filtered on and matched by the ZIP import.
 *
 * Every brand name already typed on a product is carried over: a brand row is
 * created for it and the product is linked, before the old text column goes.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('store_brands')) {
            Schema::create('store_brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo', 500)->nullable();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('store_products', 'store_brand_id')) {
            Schema::table('store_products', function (Blueprint $table) {
                $table->foreignId('store_brand_id')->nullable()->after('store_category_id')
                    ->constrained('store_brands')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('store_products', 'brand')) {
            $ids = [];
            $products = DB::table('store_products')->whereNotNull('brand')->get(['id', 'brand']);

            foreach ($products as $product) {
                $name = trim((string) $product->brand);
                if ($name === '') {
                    continue;
                }
                $key = mb_strtolower($name);

                if (! isset($ids[$key])) {
                    $base = Str::slug($name) ?: 'brand';
                    $slug = $base;
                    $n = 2;
                    while (DB::table('store_brands')->where('slug', $slug)->exists()) {
                        $slug = $base . '-' . $n++;
                    }
                    $ids[$key] = DB::table('store_brands')->insertGetId([
                        'name' => $name, 'slug' => $slug, 'sort_order' => 0,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }

                DB::table('store_products')->where('id', $product->id)->update(['store_brand_id' => $ids[$key]]);
            }

            Schema::table('store_products', function (Blueprint $table) {
                $table->dropColumn('brand');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('store_products', 'brand')) {
            Schema::table('store_products', function (Blueprint $table) {
                $table->string('brand')->nullable()->after('sku_sequence');
            });
        }

        if (Schema::hasColumn('store_products', 'store_brand_id')) {
            foreach (DB::table('store_brands')->get(['id', 'name']) as $brand) {
                DB::table('store_products')->where('store_brand_id', $brand->id)->update(['brand' => $brand->name]);
            }

            Schema::table('store_products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('store_brand_id');
            });
        }

        Schema::dropIfExists('store_brands');
    }
};
