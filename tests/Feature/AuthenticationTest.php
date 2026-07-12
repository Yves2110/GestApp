<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['label' => 'SuperAdmin']);
        Role::create(['label' => 'president']);
        Role::create(['label' => 'admin']);
        Role::create(['label' => 'services']);
        Service::create(['label' => 'Service Test']);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'role_id'    => 1,
            'service_id' => 1,
            'firstname'  => 'Admin',
            'lastname'   => 'Test',
            'email'      => 'admin@test.com',
            'number'     => '00000000',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('Home'));
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        User::create([
            'role_id'    => 1,
            'service_id' => 1,
            'firstname'  => 'Admin',
            'lastname'   => 'Test',
            'email'      => 'admin@test.com',
            'number'     => '00000000',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'role_id'    => 3,
            'service_id' => 1,
            'firstname'  => 'Inactive',
            'lastname'   => 'User',
            'email'      => 'inactive@test.com',
            'number'     => '00000001',
            'password'   => Hash::make('password'),
            'is_active'  => false,
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => 'inactive@test.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'role_id'    => 1,
            'service_id' => 1,
            'firstname'  => 'Admin',
            'lastname'   => 'Test',
            'email'      => 'admin@test.com',
            'number'     => '00000000',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $response = $this->get(route('Home'));
        $response->assertRedirect(route('login'));
    }
}
