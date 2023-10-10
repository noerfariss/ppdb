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
        Schema::create('siswa_register_forms', function (Blueprint $table) {
            $table->foreignId('siswa_register_id')->constrained('siswa_registers')->onDelete('cascade');
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->string('jawaban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_register_forms');
    }
};
