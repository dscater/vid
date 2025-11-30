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
        Schema::create('cuenta_cobrar_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cuenta_cobrar_id");
            $table->decimal("cancelado", 24, 2);
            $table->date("fecha");
            $table->time("hora");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("cuenta_cobrar_id")->on("cuenta_cobrars")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_cobrar_detalles');
    }
};
