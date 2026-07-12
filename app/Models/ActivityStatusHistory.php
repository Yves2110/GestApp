<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityStatusHistory extends Model
{
    protected $table = 'activity_status_history';

    protected $fillable = [
        'activity_id',
        'user_id',
        'from_status',
        'to_status',
        'comment',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
