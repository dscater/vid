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
        Schema::create('proforma_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("proforma_id");
            $table->unsignedBigInteger("cliente_id");
            $table->double("cantidad", 8, 2);
            $table->double("cantidad_entregada", 8, 2)->default(0);
            $table->decimal("total", 24, 2);
            $table->decimal("saldo", 24, 2);
            $table->string("estado", 255); //PENDIENTE, ATENDIDO, PARCIALMENTE ATENDIDO
            $table->integer("verificado")->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("proforma_id")->on("proformas")->references("id");
            $table->foreign("cliente_id")->on("clientes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_detalles');
    }
};
