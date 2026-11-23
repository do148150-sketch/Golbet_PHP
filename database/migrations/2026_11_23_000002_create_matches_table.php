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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');

            $table->dateTime('match_date');

            // Cuotas: lo que paga cada peso apostado si acierta
            $table->decimal('home_odds', 5, 2);
            $table->decimal('draw_odds', 5, 2);
            $table->decimal('away_odds', 5, 2);

            // Programado -> En juego -> Finalizado
            $table->string('status', 20)->default('Programado');

            // El marcador solo existe cuando el partido termina
            $table->unsignedTinyInteger('home_goals')->nullable();
            $table->unsignedTinyInteger('away_goals')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // La cartelera siempre filtra por estado y ordena por fecha
            $table->index('status');
            $table->index('match_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
