<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Seen by an admin" is not the same thing as a sales stage.
 *
 * The bell used to count leads whose status was 'new', which meant clearing the
 * badge required moving a lead to 'contacted' — falsifying the pipeline just to
 * silence a notification. read_at keeps the two apart.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('status');
            $table->index('read_at');
        });

        // Everything that predates this feature is treated as already seen —
        // otherwise the badge would open on an inbox of historic leads.
        \Illuminate\Support\Facades\DB::table('leads')
            ->whereNull('read_at')
            ->where('status', '!=', 'new')
            ->update(['read_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
            $table->dropColumn('read_at');
        });
    }
};
