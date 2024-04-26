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
        Schema::create('barbershops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('ubication')->unique();
            $table->unsignedBigInteger('gestor_id')->nullable(); // Cambiado de 'idgestor' a 'gestor_id'
            $table->string('barber');
            $table->timestamps();

            $table->foreign('gestor_id')
            ->references('id')
            ->on('users')
            ->when(function ($query) {
                // Condición: solo usuarios con el rol 'Gestor'
                $query->where('role', 'Gestor');
            })
            ->onDelete('set null')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbershop');
    }
};
