<?php

namespace Tests\Unit;

use App\Models\Objective;
use App\Models\UnderObjective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectiveModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_objective_has_fillable_attributes(): void
    {
        $fillable = (new Objective())->getFillable();

        $this->assertContains('label', $fillable);
        $this->assertContains('role_id', $fillable);
    }

    public function test_objective_has_many_under_objectives(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new Objective())->underObjectives()
        );
    }

    public function test_objective_has_many_activities(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new Objective())->activities()
        );
    }

    public function test_under_objective_has_fillable_attributes(): void
    {
        $fillable = (new UnderObjective())->getFillable();

        $this->assertContains('label', $fillable);
        $this->assertContains('objective_id', $fillable);
    }

    public function test_under_objective_belongs_to_objective(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            (new UnderObjective())->objective()
        );
    }

    public function test_under_objective_has_many_activities(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            (new UnderObjective())->activities()
        );
    }
}
