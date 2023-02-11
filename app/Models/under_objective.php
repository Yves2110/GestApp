<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class under_objective extends Model
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
        return $this->hasMany(Activities::class);
    }
}
