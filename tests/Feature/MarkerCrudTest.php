<?php

namespace Tests\Feature;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_view_marker_index(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('markers.index'));

        $this->assertTrue($user->can('viewAny', Marker::class));
        $response->assertOk();
    }

    public function test_viewer_cannot_access_marker_index(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('markers.index'));

        $this->assertFalse($user->can('viewAny', Marker::class));
        $response->assertForbidden();
    }

    public function test_editor_can_create_a_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('markers.store'), [
                'type' => Marker::TYPE_PLACE,
                'status' => Marker::STATUS_ACTIVE,
                'title' => 'Marcador nuevo',
                'address' => 'Direccion 123',
                'latitude' => '-32.8896767',
                'longitude' => '-68.8448381',
                'notes' => 'Nota inicial',
            ]);

        $this->assertTrue($user->can('create', Marker::class));
        $response->assertRedirect(route('markers.index'));

        $this->assertDatabaseHas('markers', [
            'user_id' => $user->id,
            'title' => 'Marcador nuevo',
        ]);
    }

    public function test_editor_can_see_markers_from_other_users(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $otherUser = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $ownMarker = Marker::factory()->create([
            'user_id' => $user->id,
            'title' => 'Propio',
        ]);

        $otherMarker = Marker::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Ajeno',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('markers.index'));

        $this->assertTrue($user->can('viewAny', Marker::class));
        $response->assertSeeText($ownMarker->title);
        $response->assertSeeText($otherMarker->title);
    }

    public function test_viewer_cannot_create_a_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('markers.store'), [
                'type' => Marker::TYPE_PLACE,
                'status' => Marker::STATUS_ACTIVE,
                'title' => 'Marcador no permitido',
                'address' => 'Direccion 123',
                'latitude' => '-32.8896767',
                'longitude' => '-68.8448381',
                'notes' => 'Nota inicial',
            ]);

        $this->assertFalse($user->can('create', Marker::class));
        $response->assertForbidden();

        $this->assertDatabaseMissing('markers', [
            'title' => 'Marcador no permitido',
        ]);
    }

    public function test_editor_cannot_edit_another_users_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $otherUser = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('markers.edit', $marker));

        $this->assertFalse($user->can('update', $marker));
        $response->assertForbidden();
    }

    public function test_editor_cannot_update_another_users_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $otherUser = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Original',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('markers.update', $marker), [
                'type' => Marker::TYPE_PLACE,
                'status' => Marker::STATUS_ACTIVE,
                'title' => 'Cambio no permitido',
                'address' => 'Direccion 123',
                'latitude' => '-32.8896767',
                'longitude' => '-68.8448381',
                'notes' => 'Nota',
            ]);

        $this->assertFalse($user->can('update', $marker));
        $response->assertForbidden();

        $this->assertDatabaseHas('markers', [
            'id' => $marker->id,
            'title' => 'Original',
        ]);
    }

    public function test_editor_cannot_remove_another_users_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $otherUser = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => $otherUser->id,
            'status' => Marker::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('markers.destroy', $marker));

        $this->assertFalse($user->can('delete', $marker));
        $response->assertForbidden();

        $this->assertDatabaseHas('markers', [
            'id' => $marker->id,
            'status' => Marker::STATUS_ACTIVE,
        ]);
    }

    public function test_destroy_marks_marker_as_removed(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => $user->id,
            'status' => Marker::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('markers.destroy', $marker));

        $this->assertTrue($user->can('delete', $marker));
        $response->assertRedirect(route('markers.index'));

        $this->assertDatabaseHas('markers', [
            'id' => $marker->id,
            'status' => Marker::STATUS_REMOVED,
        ]);
    }

    public function test_admin_can_remove_another_users_marker(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => User::factory()->create([
                'role' => User::ROLE_EDITOR,
            ])->id,
            'status' => Marker::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('markers.destroy', $marker));

        $this->assertTrue($admin->can('delete', $marker));
        $response->assertRedirect(route('markers.index'));

        $this->assertDatabaseHas('markers', [
            'id' => $marker->id,
            'status' => Marker::STATUS_REMOVED,
        ]);
    }
}
