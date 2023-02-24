<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'objective_id',
        'under_objective_id',
        'periode_id',
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

    public function tdr()
    {
        return $this->hasMany(Tdr::class);
    }

    public function rapport()
    {
        return $this->hasMany(Rapport::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function activity_variable()
    {
        return $this->hasMany(activity_variable::class);
    }

    public function final_status()
    {
        return $this->belongsTo(Final_status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
