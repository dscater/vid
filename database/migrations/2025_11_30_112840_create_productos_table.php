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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("codigo", 200)->unique();
            $table->string("nombre", 500);
            $table->integer("unidades_caja");
            $table->text("descripcion")->nullable();
            $table->unsignedBigInteger("categoria_id");
            $table->unsignedBigInteger("marca_id");
            $table->decimal("precio", 24, 2);
            $table->decimal("precio_ppp", 24, 2)->nullable();
            $table->integer("ppp")->default(0); // 0:INACTIVO | 1: ACTIVO
            $table->unsignedBigInteger("unidad_medida_id");
            $table->integer("estado")->default(1);
            $table->string("imagen")->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("categoria_id")->on("categorias")->references("id");
            $table->foreign("marca_id")->on("marcas")->references("id");
            $table->foreign("unidad_medida_id")->on("unidad_medidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
