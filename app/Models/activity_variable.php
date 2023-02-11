<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activity_variable extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity_id',
        'number_of_participants',
        'number_of_trainor',
        'number_of_days',
        'place',
    ];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
