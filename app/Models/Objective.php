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

    public function under_objective()
    {
        return $this->hasMany(under_objective::class);
    }

    // public function activities()
    // {
    //     return $this->hasMany(Activities::class);
    // }
}
