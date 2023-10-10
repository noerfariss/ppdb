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
        Schema::create('siswa_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahunajar_id')->constrained('tahun_ajars')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nomor_register')->index();
            $table->timestamps();
            $table->unique(['tahunajar_id', 'siswa_id', 'nomor_register']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_registers');
    }
};
