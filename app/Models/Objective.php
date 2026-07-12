<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'label',
    ];

    public function underObjectives()
    {
        return $this->hasMany(UnderObjective::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'objective_id');
    }
}
