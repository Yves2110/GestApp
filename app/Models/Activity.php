<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    const WF_DRAFT     = 'draft';
    const WF_PENDING   = 'pending';
    const WF_VALIDATED = 'validated';
    const WF_REJECTED  = 'rejected';

    const WORKFLOW_LABELS = [
        self::WF_DRAFT     => 'Brouillon',
        self::WF_PENDING   => 'En attente',
        self::WF_VALIDATED => 'Validé',
        self::WF_REJECTED  => 'Rejeté',
    ];

    const WORKFLOW_TRANSITIONS = [
        self::WF_DRAFT     => [self::WF_PENDING],
        self::WF_PENDING   => [self::WF_VALIDATED, self::WF_REJECTED],
        self::WF_VALIDATED => [],
        self::WF_REJECTED  => [self::WF_DRAFT],
    ];

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
        'workflow_status',
        'submitted_by',
        'validated_by',
        'submitted_at',
        'validated_at',
        'rejection_reason',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::WORKFLOW_TRANSITIONS[$this->workflow_status] ?? [], true);
    }

    public function getWorkflowLabelAttribute(): string
    {
        return self::WORKFLOW_LABELS[$this->workflow_status] ?? $this->workflow_status;
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function underObjective()
    {
        return $this->belongsTo(UnderObjective::class);
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

    public function activityVariables()
    {
        return $this->hasMany(ActivityVariable::class);
    }

    public function finalStatus()
    {
        return $this->hasOne(FinalStatus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ActivityStatusHistory::class)->latest();
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
