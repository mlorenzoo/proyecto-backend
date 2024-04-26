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
        // Tabla Barberos
        Schema::create('barbers', function (Blueprint $table) {
            $table->primary('id');
            $table->foreignId('id')->constrained('users')->onDelete('cascade');
            $table->string('bio')->nullable();
            $table->string('experience')->nullable();
            $table->string('specialties')->nullable();
            $table->string('pics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
