<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orden_ventas', function (Blueprint $table) {
            $table->index(['cliente_id', 'fecha'], 'idx_ordenventas_cliente_fecha');
        });
    }

    public function down()
    {
        Schema::table('orden_ventas', function (Blueprint $table) {
            $table->dropIndex('idx_ordenventas_cliente_fecha');
        });
    }
};
