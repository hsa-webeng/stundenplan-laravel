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
        Schema::create('blockierte_zeiten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doz_id')->constrained('dozenten');
            $table->integer('wochentag'); // 1 = Montag, 2 = Dienstag, ..., 7 = Sonntag
            $table->time('block_start'); // Beginn der blockierten Zeit
            $table->time('block_end'); // Ende der blockierten Zeit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blockierte_zeiten');
    }
};
