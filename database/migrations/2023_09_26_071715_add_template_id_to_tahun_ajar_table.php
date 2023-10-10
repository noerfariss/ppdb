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
        Schema::table('tahun_ajars', function (Blueprint $table) {
            $table->foreignId('template_id')->after('slug')->constrained('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_ajars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_id');
        });
    }
};
