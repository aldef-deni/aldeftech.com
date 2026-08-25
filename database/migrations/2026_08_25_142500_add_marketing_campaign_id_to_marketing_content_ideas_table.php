<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_content_ideas', function (Blueprint $table) {
            $table->foreignId('marketing_campaign_id')
                ->nullable()
                ->after('id')
                ->constrained('marketing_campaigns')
                ->nullOnDelete();

            $table->index(['marketing_campaign_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('marketing_content_ideas', function (Blueprint $table) {
            $table->dropForeign(['marketing_campaign_id']);
            $table->dropIndex(['marketing_content_ideas_marketing_campaign_id_status_index']);
            $table->dropColumn('marketing_campaign_id');
        });
    }
};
