<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'portfolio_projects')) {
                $table->json('portfolio_projects')->nullable()->after('proposal_profile');
            }
        });

        Schema::table('upwork_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('upwork_jobs', 'opener_hooks')) {
                $table->json('opener_hooks')->nullable()->after('cover_letter');
            }
            if (!Schema::hasColumn('upwork_jobs', 'milestones')) {
                $table->json('milestones')->nullable()->after('task_breakdown');
            }
            if (!Schema::hasColumn('upwork_jobs', 'matched_portfolio')) {
                $table->json('matched_portfolio')->nullable()->after('milestones');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'portfolio_projects')) {
                $table->dropColumn('portfolio_projects');
            }
        });

        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->dropColumn(['opener_hooks', 'milestones', 'matched_portfolio']);
        });
    }
};
