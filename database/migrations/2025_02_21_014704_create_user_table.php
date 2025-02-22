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
        // En la migración
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('name');
            $table->string('lastname');
            $table->string('telf');
            $table->string('direccion');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
