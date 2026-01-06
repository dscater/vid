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
        Schema::create('ajuste_reposicions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("ajuste_id");
            $table->unsignedBigInteger("sucursal_id");
            $table->unsignedBigInteger("producto_id");
            $table->double("cantidad", 8, 2);
            $table->date("fecha")->nullable();
            $table->timestamps();

            $table->foreign("ajuste_id")->on("ajustes")->references("id");
            $table->foreign("sucursal_id")->on("sucursals")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_reposicions');
    }
};
