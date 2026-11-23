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
        Schema::table('users', function (Blueprint $table) {

            // Saldo virtual en FutCoins. Nunca es dinero real.
            $table->decimal('balance', 12, 2)->default(100000)->after('role_id');

            // Borrado logico, igual que en equipos: el usuario no se destruye
            $table->boolean('is_active')->default(true)->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'is_active']);
        });
    }
};
