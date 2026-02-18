<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero actualizar los valores existentes al nuevo esquema
        DB::table('appointments')->where('status', 'pendiente')->update(['status' => 'aprobada']);
        DB::table('appointments')->where('status', 'confirmada')->update(['status' => 'aprobada']);
        DB::table('appointments')->where('status', 'en_proceso')->update(['status' => 'reagendada']);
        DB::table('appointments')->where('status', 'completada')->update(['status' => 'aprobada']);
        DB::table('appointments')->where('status', 'no_presentado')->update(['status' => 'cancelada']);
        // 'cancelada' se queda como está

        // Luego modificar la columna
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['aprobada', 'reagendada', 'cancelada'])
                ->default('aprobada')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al esquema anterior
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'pendiente',
                'confirmada',
                'en_proceso',
                'completada',
                'cancelada',
                'no_presentado'
            ])->default('pendiente')->change();
        });
    }
};
