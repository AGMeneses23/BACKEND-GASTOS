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
        Schema::create('gasto', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id')->on('users')->onDelete('cascade'); //Eliminar los gastos si el usuario se borra

            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('categorias')->onDelete('cascade'); //Eliminar los gastos si la categoria se borra

            $table->date('fecha');
            $table->decimal('monto', 10, 2); //Especificar precisión para los valores decimales 
            $table->string('descripcion', 255); //Ajustar tipo y permitir nulos para descripciones

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto');
    }
};
