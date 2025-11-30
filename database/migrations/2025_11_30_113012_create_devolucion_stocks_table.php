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
        Schema::create('devolucion_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nro");
            $table->string("codigo")->unique();
            $table->unsignedBigInteger("sucursal_id");
            $table->double("cantidad_total", 8, 2);
            $table->decimal("total", 24, 2);
            $table->double("cantidad_total_v", 8, 2);
            $table->decimal("total_v", 24, 2);
            $table->date("fecha");
            $table->time("hora");
            $table->text("observaciones")->nullable();
            $table->string("estado");
            $table->unsignedBigInteger("user_id");
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("sucursal_id")->on("sucursals")->references("id");
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucion_stocks');
    }
};
