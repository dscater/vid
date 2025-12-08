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
        Schema::create('orden_ventas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nro");
            $table->string("codigo")->unique();
            $table->unsignedBigInteger("sucursal_id");
            $table->unsignedBigInteger("cliente_id");
            $table->date("fecha");
            $table->time("hora");
            $table->double("cantidad_total", 8, 2);
            $table->decimal("total", 24, 2);
            $table->decimal("total_st", 24, 2);
            $table->integer("solicitud_descuento")->default(0);
            $table->integer("solicitud_sw")->default(0)->nullable();
            $table->unsignedBigInteger("user_ap")->nullable();
            $table->decimal("monto_solicitud", 24, 2)->default(0)->nullable();
            $table->decimal("descuento", 24, 2)->default(0)->nullable();
            $table->decimal("cancelado", 24, 2)->default(0)->nullable();
            $table->decimal("total_f", 24, 2);
            $table->decimal("cambio", 24, 2)->default(0)->nullable();
            $table->string("forma_pago");
            $table->string("cs_f");
            $table->text("observaciones")->nullable();
            $table->string("estado"); // para controlar:PENDIENTE, APROBADO, FINALIZADO, RECHAZADO
            $table->integer("verificado")->default(0); // 0:PENDIENTE, 1: APROBADO, 2: FINALIZADO, 3: RECHAZADO
            $table->unsignedBigInteger("user_id");
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("sucursal_id")->on("sucursals")->references("id");
            $table->foreign("cliente_id")->on("clientes")->references("id");
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_ventas');
    }
};
