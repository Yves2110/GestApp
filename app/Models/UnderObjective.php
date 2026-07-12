<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnderObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'objective_id',
        'label',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
