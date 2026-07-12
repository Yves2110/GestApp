<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['label'];

    /**
     * Accesseur de compatibilité : de nombreuses vues utilisent $service->service
     * alors que la colonne en base s'appelle "label".
     */
    public function getServiceAttribute()
    {
        return $this->label;
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'service_id');
    }
}
