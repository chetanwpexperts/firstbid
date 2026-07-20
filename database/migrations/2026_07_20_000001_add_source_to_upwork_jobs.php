<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->string('source', 20)->default('webhook')->after('user_id'); // webhook | email
        });
    }

    public function down(): void
    {
        Schema::table('upwork_jobs', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
