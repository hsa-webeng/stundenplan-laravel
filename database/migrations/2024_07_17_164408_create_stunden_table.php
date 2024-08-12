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
        Schema::create('stunden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurs_id')->constrained('kurse')->cascadeOnDelete();
            $table->integer('wochentag'); // 0 = Montag, 1 = Dienstag, ..., 5 = Samstag
            $table->time('block_start'); // Stundenbeginn
            $table->time('block_end'); // Stundenende
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stunden');
    }
};
