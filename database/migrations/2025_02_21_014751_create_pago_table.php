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
        Schema::create('pago', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->date('feca_pago');
            $table->string('metodo');
            $table->decimal('monto', 8, 2);
            $table->unsignedBigInteger('id_reserva')->nullable();

            $table->foreign('id_reserva')->references('id_reserva')->on('reserva')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
