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
        Schema::table('students', function (Blueprint $table) {
            $table->string('group')->nullable()->after('idnp');
            $table->unsignedTinyInteger('studyYear')->nullable()->after('group');
            $table->string('speciality')->nullable()->after('studyYear');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('group');
            $table->dropColumn('studyYear');
            $table->dropColumn('speciality');
        });
    }
};
