<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upwork_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('ciphertext')->nullable()->unique(); // dedup key from UpHunt
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('job_url', 500)->nullable();
            $table->string('job_type', 20)->nullable();          // HOURLY | FIXED
            $table->string('budget_display')->nullable();        // "$30-60/hr" or "$500 fixed"
            $table->string('contractor_tier')->nullable();
            $table->string('client_country')->nullable();
            $table->decimal('client_score', 3, 1)->nullable();
            $table->unsignedInteger('client_hires')->nullable();
            $table->boolean('payment_verified')->default(false);
            $table->unsignedTinyInteger('uphunt_score')->nullable();
            $table->json('screening_questions')->nullable();
            $table->json('raw_payload');
            $table->text('cover_letter')->nullable();
            $table->json('question_answers')->nullable();
            $table->string('bid_suggestion')->nullable();
            $table->string('status', 30)->default('received');   // received|skipped|generated|notified|failed
            $table->string('skip_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upwork_jobs');
    }
};
