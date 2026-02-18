<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id')->constrained('clients')->nullOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            $table->string('name')->after('id');
            $table->string('phone', 20)->after('name');
        });
    }
};
