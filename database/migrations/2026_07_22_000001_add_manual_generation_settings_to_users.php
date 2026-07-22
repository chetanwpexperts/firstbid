<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('min_score_operator', 3)->default('>=')->after('min_score'); // '>' | '>='
            $table->boolean('auto_generate')->default(false)->after('min_score_operator');
            $table->boolean('skip_unverified_payment')->default(true)->after('auto_generate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['min_score_operator', 'auto_generate', 'skip_unverified_payment']);
        });
    }
};
