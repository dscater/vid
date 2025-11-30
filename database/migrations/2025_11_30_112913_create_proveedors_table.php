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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string("razon_social");
            $table->string("nombre_com")->nullable();
            $table->string("nit");
            $table->string("moneda");
            $table->string("fono_emp")->nullable();
            $table->string("correo");
            $table->string("dir");
            $table->string("ciudad");
            $table->string("tipo");
            $table->integer("estado")->default(1);
            $table->text("observaciones");
            $table->json("categorias");
            $table->json("marcas");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
