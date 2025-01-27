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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->decimal('monto');
            $table->unsignedBigInteger('id_categoria');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->foreign('id_users')->references('id')->on('users');
            $table->foreign('id_categoria')->references('id')->on('categorias');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
