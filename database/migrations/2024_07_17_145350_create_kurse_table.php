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
        Schema::create('kurse', function (Blueprint $table) {
            $table->id();
            $table->string('kurs_name');
            $table->foreignId('doz_id')->constrained('dozenten');
            $table->foreignId('stdg_id')->constrained('studiengänge');
            $table->integer('semester');
            $table->integer('sws'); // Semesterwochenstunden
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurse');
    }
};
