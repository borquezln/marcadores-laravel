<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertNotNull($user->refresh()->last_login_at);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_pending_users_can_not_authenticate(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_PENDING,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_disabled_users_can_not_authenticate(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_DISABLED,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_authenticated_users_are_logged_out(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_DISABLED,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_active_users_can_access_map(): void
    {
        foreach ([User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_VIEWER] as $role) {
            $user = User::factory()->create([
                'role' => $role,
            ]);

            $this->actingAs($user)
                ->get('/map')
                ->assertOk();
        }
    }

    public function test_role_middleware_allows_only_configured_roles(): void
    {
        Route::get('/role-test', fn (): string => 'ok')
            ->middleware(['web', 'auth', 'active', 'role:admin']);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $this->actingAs($admin)
            ->get('/role-test')
            ->assertOk();

        $this->actingAs($viewer)
            ->get('/role-test')
            ->assertForbidden();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
        ]);
    }

    public function test_login_screen_shows_register_link(): void
    {
        $response = $this->get('/login');

        $response->assertSee(route('register'));
        $response->assertSeeText('Crear cuenta');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
