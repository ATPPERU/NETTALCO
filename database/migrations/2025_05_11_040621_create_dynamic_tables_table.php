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
       Schema::create('dynamic_tables', function (Blueprint $table) {
        $table->id();
        $table->string('table_name');  // Nombre de la tabla dinámica
        $table->json('headers');       // Cabeceras de la tabla
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_tables');
    }
};
