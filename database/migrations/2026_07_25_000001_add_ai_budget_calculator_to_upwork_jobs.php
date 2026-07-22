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
            $table->string('estimated_budget')->nullable()->after('bid_suggestion');
            $table->string('estimated_duration')->nullable()->after('estimated_budget');
            $table->text('budget_reasoning')->nullable()->after('estimated_duration');
            $table->json('task_breakdown')->nullable()->after('budget_reasoning');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_budget',
                'estimated_duration',
                'budget_reasoning',
                'task_breakdown',
            ]);
        });
    }
};
