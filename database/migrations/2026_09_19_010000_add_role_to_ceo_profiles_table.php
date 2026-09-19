<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gives each leadership row a role so the single "CEO profile" record can be
 * joined by a commissioner without a second table or a second admin menu.
 *
 * Additive only: existing rows keep their data and are backfilled as the CEO
 * by the column default, so the homepage keeps showing Deni Afrizal unchanged.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('ceo_profiles', 'role')) {
            return;
        }

        Schema::table('ceo_profiles', function (Blueprint $table) {
            $table->string('role')->default('ceo')->after('id')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('ceo_profiles', 'role')) {
            return;
        }

        Schema::table('ceo_profiles', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
