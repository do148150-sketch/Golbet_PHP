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
        Schema::create('bets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('match_id')->constrained('matches');

            // Cuanto apostó, en FutCoins
            $table->decimal('amount', 12, 2);

            // Local / Empate / Visitante
            $table->string('pick', 20);

            // La cuota se CONGELA al apostar: si el admin la cambia despues,
            // esta apuesta sigue pagando la que vio el apostador.
            $table->decimal('odds_at_placement', 5, 2);

            // Pendiente -> Ganada / Perdida
            $table->string('status', 20)->default('Pendiente');

            $table->timestamps();

            // "Mis apuestas" filtra por usuario; la resolucion, por partido
            $table->index(['user_id', 'status']);
            $table->index(['match_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
