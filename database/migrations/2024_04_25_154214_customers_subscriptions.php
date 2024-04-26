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
        // Tabla Suscripción Cliente
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('sub_id')->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('pay_id')->constrained('payments')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('state', ['Activa', 'Cancelada', 'Finalizada']);
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
