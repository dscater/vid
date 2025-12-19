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
        Schema::create('proforma_detalle_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("proforma_id");
            $table->unsignedBigInteger("proforma_detalle_id");
            $table->unsignedBigInteger("proforma_producto_id");
            $table->unsignedBigInteger("producto_id");
            $table->unsignedBigInteger("unidad_medida_id");
            $table->double("cantidad", 8, 2)->nullable()->default(NULL);
            $table->double("cantidad_entregada", 8, 2)->nullable()->default(NULL);
            $table->double("resta", 8, 2)->default(0);
            $table->decimal("precio", 24, 2)->nullable()->default(NULL);
            $table->decimal("subtotal", 24, 2)->nullable()->default(NULL);
            $table->integer("verificado")->default(0)->nullable();
            $table->timestamps();

            $table->foreign("proforma_id")->on("proformas")->references("id");
            $table->foreign("proforma_detalle_id")->on("proforma_detalles")->references("id");
            $table->foreign("proforma_producto_id")->on("proforma_productos")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("unidad_medida_id")->on("unidad_medidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_detalle_productos');
    }
};
