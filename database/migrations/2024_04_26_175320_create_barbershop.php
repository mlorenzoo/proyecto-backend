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
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->unsignedBigInteger('gestor_id')->nullable();
            $table->unsignedBigInteger('barber_id')->nullable();
            $table->timestamps();

            $table->foreign('gestor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
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
