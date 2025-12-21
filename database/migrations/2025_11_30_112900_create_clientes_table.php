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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string("razon_social", 700);
            $table->string("tipo");
            $table->string("nit");
            $table->string("nombre_punto", 700);
            $table->string("nombre_prop");
            $table->string("ci_prop");
            $table->string("correo")->nullable();
            $table->string("cel");
            $table->string("fono");
            $table->string("dir");
            $table->text("ubicacion");
            $table->string("ciudad");
            $table->string("categoria")->default("");
            $table->integer("rank")->nullable()->default(NULL);
            $table->double("score", 24, 4)->nullable()->default(NULL);
            $table->double("factor", 8, 4)->nullable()->default(NULL);
            $table->json("contactos")->nullable();
            $table->integer("estado")->default(1);
            $table->integer("credito")->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
