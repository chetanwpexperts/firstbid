<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('webhook_token', 40)->nullable()->unique()->after('password');
            $table->text('proposal_profile')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->unsignedTinyInteger('min_score')->default(7);
            $table->string('plan', 20)->default('free');           // free | pro
            $table->unsignedInteger('letters_used')->default(0);   // resets monthly
            $table->unsignedInteger('letters_quota')->default(15); // free plan default
            $table->timestamp('quota_reset_at')->nullable();
        });

        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'webhook_token', 'proposal_profile', 'telegram_chat_id',
                'min_score', 'plan', 'letters_used', 'letters_quota', 'quota_reset_at',
            ]);
        });
    }
};
