<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    public function test_guests_are_redirected_to_login_from_every_admin_route(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/reservations')->assertRedirect('/login');
        $this->get('/admin/menu-items')->assertRedirect('/login');
    }

    public function test_an_admin_can_reach_the_dashboard(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee($admin->name);
    }

    public function test_a_logged_in_non_admin_is_forbidden_from_every_admin_route(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->assertFalse($user->isAdmin());

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/reservations')->assertForbidden();
        $this->actingAs($user)->get('/admin/menu-items')->assertForbidden();
        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_the_legacy_dashboard_route_redirects_an_admin_into_the_admin_area(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_there_is_no_public_registration_route(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', ['name' => 'x', 'email' => 'x@example.com', 'password' => 'password'])
            ->assertNotFound();
    }

    public function test_is_admin_is_not_mass_assignable(): void
    {
        $user = new User(['name' => 'Test', 'email' => 't@example.com', 'password' => 'x', 'is_admin' => true]);

        $this->assertFalse($user->isAdmin(), 'is_admin must never be settable through mass assignment.');
    }
}
