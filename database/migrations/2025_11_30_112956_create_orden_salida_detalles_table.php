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
        Schema::create('orden_salida_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("orden_salida_id");
            $table->unsignedBigInteger("producto_id");
            $table->double("cantidad", 8, 2);
            $table->double("cantidad_fisica", 8, 2);
            $table->decimal("costo", 24, 2);
            $table->decimal("subtotal", 24, 2);
            $table->integer("verificado")->default(0);
            $table->unsignedBigInteger("sucursal_ajuste")->nullable();
            $table->string("motivo")->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("orden_salida_id")->on("orden_salidas")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("sucursal_ajuste")->on("sucursals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_salida_detalles');
    }
};
