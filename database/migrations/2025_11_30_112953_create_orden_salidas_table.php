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
        Schema::create('orden_salidas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nro");
            $table->string("codigo")->unique();
            $table->unsignedBigInteger("sucursal_id");
            $table->unsignedBigInteger("user_sol");
            $table->unsignedBigInteger("user_ap");
            $table->date("fecha");
            $table->time("hora");
            $table->text("observaciones");
            $table->string("estado"); // PENDIENTE, APROBADO, APROBADO CON OBSERVACIONES
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("sucursal_id")->on("sucursals")->references("id");
            $table->foreign("user_sol")->on("users")->references("id");
            $table->foreign("user_ap")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_salidas');
    }
};
