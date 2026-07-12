<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\Service;
use App\Models\UnderObjective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_has_fillable_attributes(): void
    {
        $fillable = (new Activity())->getFillable();

        $this->assertContains('label', $fillable);
        $this->assertContains('service_id', $fillable);
        $this->assertContains('objective_id', $fillable);
        $this->assertContains('under_objective_id', $fillable);
        $this->assertContains('periode_id', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_activity_belongs_to_service(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            (new Activity())->service()
        );
    }

    public function test_activity_belongs_to_objective(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            (new Activity())->objective()
        );
    }

    public function test_activity_belongs_to_under_objective(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            (new Activity())->underObjective()
        );
    }

    public function test_activity_belongs_to_periode(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            (new Activity())->periode()
        );
    }

    public function test_activity_has_many_tdrs(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new Activity())->tdr()
        );
    }

    public function test_activity_has_many_rapports(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new Activity())->rapport()
        );
    }

    public function test_activity_has_many_activity_variables(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new Activity())->activityVariables()
        );
    }
}
