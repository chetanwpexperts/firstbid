<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('niche')->nullable()->after('email');
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('niche');
            $table->string('years_experience')->nullable()->after('hourly_rate');
            $table->string('upwork_url')->nullable()->after('years_experience');
            $table->string('phone')->nullable()->after('upwork_url');
            $table->string('company_name')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['niche', 'hourly_rate', 'years_experience', 'upwork_url', 'phone', 'company_name']);
        });
    }
};
