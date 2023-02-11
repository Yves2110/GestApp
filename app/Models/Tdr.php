<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tdr extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',
        'activity_id',
        'fichier',
    ];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
