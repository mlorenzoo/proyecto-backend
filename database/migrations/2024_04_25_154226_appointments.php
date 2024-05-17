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
        // Tabla Citas
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('barber_id')->constrained('barbers')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->dateTime('date');
            $table->time('hour');
            $table->enum('state', ['programada', 'confirmada', 'completada', 'cancelada']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['barber_id', 'date', 'hour']);
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
