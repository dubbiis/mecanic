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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del propietario
            $table->string('phone'); // Teléfono
            $table->string('car'); // Marca y modelo del vehículo
            $table->string('plate')->unique(); // Matrícula (única)
            $table->date('itv_date'); // Fecha de vencimiento ITV
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
