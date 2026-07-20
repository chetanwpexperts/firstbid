<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbound_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('to_address');
            $table->string('from_address');
            $table->string('subject', 500)->nullable();
            $table->longText('html')->nullable();
            $table->unsignedSmallInteger('jobs_found')->default(0);
            $table->string('status', 30)->default('received'); // received|parsed|no_jobs|unknown_user|verification
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_emails');
    }
};
