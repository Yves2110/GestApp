<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity_id',
        'label',
    ];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
