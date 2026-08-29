<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A visitable demo is different from project_url, which points at the client's
 * own live site. Only some builds have one, and demo_url being filled is what
 * marks a project as testable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('demo_url', 500)->nullable()->after('project_url');
            $table->string('demo_username', 100)->nullable()->after('demo_url');
            $table->string('demo_password', 100)->nullable()->after('demo_username');
            $table->text('demo_note')->nullable()->after('demo_password');
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['demo_url', 'demo_username', 'demo_password', 'demo_note']);
        });
    }
};
