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
        Schema::create('reserva', function (Blueprint $table) {
            $table->bigIncrements('id_reserva');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_tour')->nullable();
            $table->date('fecha_reserva');
            $table->string('estado');
            

            $table->foreign('id_user')->references('id_user')->on('usuario')->onDelete('cascade');
            $table->foreign('id_tour')->references('id_tour')->on('tour')->onDelete('cascade');

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
