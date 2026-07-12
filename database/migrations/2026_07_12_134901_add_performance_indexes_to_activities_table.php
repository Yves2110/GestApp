<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->index('workflow_status',  'idx_activities_workflow_status');
            $table->index('service_id',       'idx_activities_service_id');
            $table->index('periode_id',       'idx_activities_periode_id');
            $table->index('status',           'idx_activities_status');
            $table->index('created_at',       'idx_activities_created_at');
            $table->index(['service_id', 'workflow_status'], 'idx_activities_service_workflow');
        });

        Schema::table('activity_status_history', function (Blueprint $table) {
            $table->index('activity_id', 'idx_ash_activity_id');
            $table->index('created_at',  'idx_ash_created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('type',    'idx_notif_type');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('idx_activities_workflow_status');
            $table->dropIndex('idx_activities_service_id');
            $table->dropIndex('idx_activities_periode_id');
            $table->dropIndex('idx_activities_status');
            $table->dropIndex('idx_activities_created_at');
            $table->dropIndex('idx_activities_service_workflow');
        });

        Schema::table('activity_status_history', function (Blueprint $table) {
            $table->dropIndex('idx_ash_activity_id');
            $table->dropIndex('idx_ash_created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notif_type');
        });
    }
};
