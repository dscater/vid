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
        Schema::create('proforma_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("proforma_id");
            $table->unsignedBigInteger("producto_id");
            $table->decimal("precio", 24, 2)->nullable();
            $table->unsignedBigInteger("unidad_medida_id");
            $table->double("stock_actual", 8, 2)->nullable();
            $table->timestamps();

            $table->foreign("proforma_id")->on("proformas")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("unidad_medida_id")->on("unidad_medidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_productos');
    }
};
