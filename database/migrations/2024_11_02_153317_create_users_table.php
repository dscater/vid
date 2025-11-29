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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("usuario", 255);
            $table->string("nombre", 255);
            $table->string("paterno", 255);
            $table->string("materno", 255)->nullable();
            $table->string("ci", 255);
            $table->string("ci_exp", 255);
            $table->string("grupo_san");
            $table->string("sexo");
            $table->string("nacionalidad");
            $table->string("profesion")->nullable();
            $table->string("cel", 255);
            $table->string("fono", 255)->nullable();
            $table->string("cel_dom", 255);
            $table->string("dir", 600);
            $table->string("latitud", 600);
            $table->string("longitud", 600);
            $table->string("correo", 255)->nullable();
            $table->string("foto", 255)->nullable();
            $table->string("carnet", 255)->nullable();
            $table->string("doc_adicional", 255)->nullable();
            $table->string('password');
            $table->string("tipo")->nullable();
            $table->unsignedBigInteger("role_id")->nullable();
            $table->integer("acceso");
            $table->date("fecha_registro");
            $table->integer("status")->default(1);
            $table->timestamps();

            $table->foreign("role_id")->on("roles")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
