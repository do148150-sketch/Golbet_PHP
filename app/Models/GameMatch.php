<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Un partido de la cartelera.
 *
 * La clase NO se puede llamar 'Match': desde PHP 8 'match' es una palabra
 * reservada del lenguaje. Por eso se llama GameMatch y se le indica a mano
 * cual es su tabla.
 */
class GameMatch extends Model
{
    protected $table = 'matches';

    /** Estados por los que pasa un partido, en orden */
    public const SCHEDULED = 'Programado';
    public const IN_PROGRESS = 'En juego';
    public const FINISHED = 'Finalizado';

    public const STATUSES = [self::SCHEDULED, self::IN_PROGRESS, self::FINISHED];

    protected $casts = [
        'match_date' => 'datetime',
        'home_odds'  => 'decimal:2',
        'draw_odds'  => 'decimal:2',
        'away_odds'  => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function homeTeam(): BelongsTo {

        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo {

        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function bets(): HasMany {

        return $this->hasMany(Bet::class, 'match_id');
    }

    /**
     * Solo se puede apostar a un partido programado que todavia no empieza.
     */
    public function acceptsBets(): bool {

        return $this->status === self::SCHEDULED
            && $this->match_date->isFuture();
    }
}
