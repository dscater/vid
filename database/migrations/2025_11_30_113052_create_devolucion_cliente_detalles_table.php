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
        Schema::create('devolucion_cliente_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("devolucion_cliente_id");
            $table->unsignedBigInteger("producto_id");
            $table->double("cantidad", 8, 2);
            $table->decimal("costo", 24, 2);
            $table->decimal("subtotal", 24, 2);
            $table->timestamps();
            $table->foreign("devolucion_cliente_id")->on("devolucion_clientes")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucion_cliente_detalles');
    }
};
