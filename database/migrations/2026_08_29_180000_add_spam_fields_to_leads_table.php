<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('is_spam')->default(false)->after('status');
            $table->unsignedSmallInteger('spam_score')->default(0)->after('is_spam');

            // Kept so the console can show WHY something was flagged. A filter
            // that cannot explain itself gets distrusted and then ignored.
            $table->json('spam_reasons')->nullable()->after('spam_score');

            $table->string('ip_address', 45)->nullable()->after('source');

            $table->index('is_spam');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['is_spam']);
            $table->dropColumn(['is_spam', 'spam_score', 'spam_reasons', 'ip_address']);
        });
    }
};
