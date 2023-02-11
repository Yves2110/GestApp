<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;
    protected $fillable = [
        'objective_id',
        'under_objective_id',
        'activity_id',
        'fichier',
        'number',
    ];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
