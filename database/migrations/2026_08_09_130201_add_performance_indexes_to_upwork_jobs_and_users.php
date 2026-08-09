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
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'created_at'], 'idx_jobs_user_status_created');
            $table->index('job_url', 'idx_jobs_job_url');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('webhook_token', 'idx_users_webhook_token');
            $table->index('is_approved', 'idx_users_is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->dropIndex('idx_jobs_user_status_created');
            $table->dropIndex('idx_jobs_job_url');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_webhook_token');
            $table->dropIndex('idx_users_is_approved');
        });
    }
};
