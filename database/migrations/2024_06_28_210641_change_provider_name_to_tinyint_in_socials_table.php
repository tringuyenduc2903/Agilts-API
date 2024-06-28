<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->dropColumn('provider_name');
        });
        Schema::table('socials', function (Blueprint $table) {
            $table->unsignedTinyInteger('provider_name')->after('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->dropColumn('provider_name');
        });
        Schema::table('socials', function (Blueprint $table) {
            $table->string('provider_name', 10);
        });
    }
};
