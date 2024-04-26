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
        // Tabla Pagos
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('pay_date');
            $table->decimal('amount', 10, 2);
            $table->string('pay_method');
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
