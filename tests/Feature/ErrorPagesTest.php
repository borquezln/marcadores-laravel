<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_forbidden_page_is_in_spanish_and_has_navigation_actions(): void
    {
        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $this
            ->actingAs($viewer)
            ->get(route('admin.users.index'))
            ->assertForbidden()
            ->assertSeeText('Acceso no permitido')
            ->assertSeeText('Ir al panel')
            ->assertSeeText('Cerrar sesión')
            ->assertSeeText('Volver atrás');
    }

    public function test_not_found_page_is_in_spanish_and_has_navigation_actions(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $this
            ->actingAs($user)
            ->get('/ruta-inexistente')
            ->assertNotFound()
            ->assertSeeText('Página no encontrada')
            ->assertSeeText('Ir al inicio')
            ->assertSeeText('Volver atrás');
    }
}
