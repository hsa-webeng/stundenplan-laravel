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
        Schema::create('dozenten', function (Blueprint $table) {
            $table->id();
            $table->string('dozent_vorname');
            $table->string('dozent_nachname');

            // 3 possible values: null = not even started, 0 = started but not finished, 1 = finished
            $table->boolean('plan_abgegeben')->nullable()->default(null);

            $table->foreignId('user_id')->nullable()->constrained("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dozenten');
    }
};
