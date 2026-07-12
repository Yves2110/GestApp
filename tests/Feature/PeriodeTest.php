<?php

namespace Tests\Feature;

use App\Models\Periode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestFixtures;
use Tests\TestCase;

class PeriodeTest extends TestCase
{
    use RefreshDatabase, CreatesTestFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createFixtures();
    }

    public function test_guest_cannot_view_periodes(): void
    {
        $this->get(route('trimestre'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_periodes(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('trimestre'))
            ->assertStatus(200);
    }

    public function test_authenticated_user_can_create_periode(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('trimestre.store'), ['label' => 'T2 2025']);

        $response->assertRedirect();
        $this->assertDatabaseHas('periodes', ['label' => 'T2 2025']);
    }

    public function test_periode_store_requires_label(): void
    {
        $this->actingAs($this->superAdmin)
            ->post(route('trimestre.store'), [])
            ->assertSessionHasErrors(['label']);
    }

    public function test_periode_label_must_be_unique(): void
    {
        $countBefore = \App\Models\Periode::count();

        $this->actingAs($this->superAdmin)
            ->post(route('trimestre.store'), ['label' => 'Semestre Unique']);

        $this->actingAs($this->superAdmin)
            ->post(route('trimestre.store'), ['label' => 'Semestre Unique'])
            ->assertSessionHasErrors(['label']);

        $this->assertDatabaseCount('periodes', $countBefore + 1);
    }

    public function test_periode_label_max_length(): void
    {
        $this->actingAs($this->superAdmin)
            ->post(route('trimestre.store'), ['label' => str_repeat('a', 101)])
            ->assertSessionHasErrors(['label']);
    }

    public function test_trimestre_page_shows_existing_periodes(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('trimestre'));

        $response->assertStatus(200)
                 ->assertSee('T1 2025');
    }
}
