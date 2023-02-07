<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',
        'service_id',
        'objective_id',
        'under_objective_id',
        'label',
        'indicator',
        'target',
        'price',
        'source_of_funding',
        'structure',
        'status',
        'commentary',
    ];

    public function under_objective()
    {
        return $this->belongsTo(under_objective::class);
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }
}
