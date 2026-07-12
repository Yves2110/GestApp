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
            $table->string('workflow_status')->default('draft')->after('status');
            $table->unsignedBigInteger('submitted_by')->nullable()->after('workflow_status');
            $table->unsignedBigInteger('validated_by')->nullable()->after('submitted_by');
            $table->timestamp('submitted_at')->nullable()->after('validated_by');
            $table->timestamp('validated_at')->nullable()->after('submitted_at');
            $table->text('rejection_reason')->nullable()->after('validated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['workflow_status','submitted_by','validated_by','submitted_at','validated_at','rejection_reason']);
        });
    }
};
