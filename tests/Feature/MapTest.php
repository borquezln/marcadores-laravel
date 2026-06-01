<?php

namespace Tests\Feature;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_shows_only_active_markers(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        Marker::factory()->create([
            'status' => Marker::STATUS_ACTIVE,
            'title' => 'Marcador activo',
            'address' => 'Direccion visible',
            'latitude' => '-32.8896767',
            'longitude' => '-68.8448381',
        ]);

        Marker::factory()->create([
            'status' => Marker::STATUS_INACTIVE,
            'title' => 'Marcador inactivo',
        ]);

        Marker::factory()->create([
            'status' => Marker::STATUS_REMOVED,
            'title' => 'Marcador removido',
        ]);

        $this
            ->actingAs($user)
            ->get(route('map.index'))
            ->assertOk()
            ->assertSee('id="map"', false)
            ->assertSeeText('1 marcador visible')
            ->assertSee('Marcador activo')
            ->assertSee('Direccion visible')
            ->assertDontSee('Marcador inactivo')
            ->assertDontSee('Marcador removido');
    }
}
