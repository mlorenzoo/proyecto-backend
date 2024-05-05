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
            $table->unsignedBigInteger('barber_id')->nullable();
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

            $table->foreign('barber_id')
                ->references('id')
                ->on('barbers')
                ->onDelete('set null')
                ->onUpdate('cascade')
                ->when(function ($query) {
                    // Condición: solo cuando la id de barbershop sea igual a barbershop_id en la tabla barbers
                    $query->whereExists(function ($query) {
                        $query->selectRaw(1)
                            ->from('barbers')
                            ->whereColumn('barbers.barbershop_id', '=', 'barbershops.id');
                    });
                });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbershops');
    }
};
