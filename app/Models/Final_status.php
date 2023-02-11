<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Final_status extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity_id',
        'label',
        'market_number',

    ];
    
    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
