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
        // Tabla Suscripcion
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('id');
            $table->string('plan');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->string('duration');
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
