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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->dateTime('appointment_date'); // Fecha y hora de la cita
            $table->enum('service_type', [
                'revision',      // Revisión general
                'reparacion',    // Reparación
                'itv',           // ITV
                'diagnostico',   // Diagnóstico
                'mantenimiento', // Mantenimiento preventivo
                'otro'           // Otro servicio
            ])->default('revision');
            $table->enum('status', [
                'pendiente',    // Cita programada
                'confirmada',   // Cliente confirmó
                'en_proceso',   // En el taller
                'completada',   // Trabajo terminado
                'cancelada',    // Cancelada
                'no_presentado' // Cliente no se presentó
            ])->default('pendiente');
            $table->text('description')->nullable(); // Descripción del trabajo a realizar
            $table->text('work_done')->nullable(); // Trabajo realizado (se rellena al completar)
            $table->decimal('estimated_cost', 10, 2)->nullable(); // Coste estimado
            $table->decimal('final_cost', 10, 2)->nullable(); // Coste final
            $table->integer('estimated_duration')->nullable(); // Duración estimada en minutos
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();
            $table->softDeletes(); // Para mantener histórico aunque se "elimine"

            // Índices para optimizar consultas
            $table->index('appointment_date');
            $table->index('status');
            $table->index(['vehicle_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
