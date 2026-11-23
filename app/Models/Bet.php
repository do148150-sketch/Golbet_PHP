<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    /** A que resultado se le apuesta */
    public const HOME = 'Local';
    public const DRAW = 'Empate';
    public const AWAY = 'Visitante';

    public const PICKS = [self::HOME, self::DRAW, self::AWAY];

    /** Estado de la apuesta */
    public const PENDING = 'Pendiente';
    public const WON = 'Ganada';
    public const LOST = 'Perdida';

    protected $casts = [
        'amount'            => 'decimal:2',
        'odds_at_placement' => 'decimal:2',
    ];

    public function user(): BelongsTo {

        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo {

        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    /**
     * Lo que pagaria esta apuesta si acierta, con la cuota congelada.
     */
    public function potentialPayout() {

        return $this->amount * $this->odds_at_placement;
    }
}
