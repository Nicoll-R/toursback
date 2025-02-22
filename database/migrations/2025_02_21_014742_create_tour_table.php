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
        Schema::create('tour', function (Blueprint $table) {
            $table->bigIncrements('id_tour');
            $table->string('nombreto');
            $table->string('descripcion');
            $table->decimal('precio', 8, 2);
            $table->integer('duracion');
            $table->date('fecha_inicio');
            $table->string('destino');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour');
    }
};

