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
        Schema::create('solicitud_ingresos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nro");
            $table->string("codigo")->unique();
            $table->unsignedBigInteger("proveedor_id");
            $table->date("fecha_ingreso");
            $table->time("hora_ingreso");
            $table->date("fecha_sis");
            $table->time("hora_sis");
            $table->string("cs_f");
            $table->decimal("tipo_cambio", 24, 2);
            $table->decimal("gastos", 24, 2);
            $table->text("observaciones")->nullable();
            $table->text("descripcion")->nullable();
            $table->double("cantidad_total", 8, 2);
            $table->decimal("total", 24, 2);
            $table->string("estado"); // PENDIENTE,APROBADO,APROBADO CON OBSERVACIONES
            $table->unsignedBigInteger("user_id");
            $table->integer("verificado")->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("proveedor_id")->on("proveedors")->references("id");
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_ingresos');
    }
};
