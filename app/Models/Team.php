<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function homeMatches(): HasMany {

        return $this->hasMany(GameMatch::class, 'home_team_id');
    }

    public function awayMatches(): HasMany {

        return $this->hasMany(GameMatch::class, 'away_team_id');
    }
}
