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
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nro");
            $table->string("codigo")->unique();
            $table->json("sucursal_ids");
            $table->date("fecha");
            $table->time("hora");
            $table->double("cantidad_total", 8, 2)->nullable();
            $table->decimal("total", 24, 2)->nullable();
            $table->text("observaciones")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
