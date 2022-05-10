<?php

namespace App\Models;

use App\Models\TournamentRecurrence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $guarded = [];

    public function tournament_type() {
        return $this->belongsTo(TournamentType::class);
    }

    public function tournament_platform() {
        return $this->belongsTo(TournamentPlatform::class);
    }

    public function tournament_recurrence() {
        return $this->belongsTo(TournamentRecurrence::class);
    }
}
