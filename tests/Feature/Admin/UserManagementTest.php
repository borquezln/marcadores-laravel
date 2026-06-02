<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'name' => 'Pending Viewer',
            'email' => 'pending@example.com',
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
            'last_login_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.users.index'));

        $response
            ->assertOk()
            ->assertSeeText('Usuarios')
            ->assertSeeText($admin->name)
            ->assertSeeText($user->name)
            ->assertSeeText($user->email)
            ->assertSeeText('Viewer')
            ->assertSeeText('Pendiente')
            ->assertSeeText('No editable');
    }

    public function test_last_login_is_shown_in_application_timezone(): void
    {
        config(['app.timezone' => 'America/Argentina/Buenos_Aires']);

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Login User',
            'last_login_at' => '2026-06-02 15:00:00',
        ]);

        $this
            ->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSeeText('GMT-03:00')
            ->assertDontSeeText('GMT+00:00');
    }

    public function test_editor_cannot_access_user_administration(): void
    {
        $editor = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $response = $this
            ->actingAs($editor)
            ->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_viewer_cannot_access_user_administration(): void
    {
        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $response = $this
            ->actingAs($viewer)
            ->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_editor_cannot_update_users(): void
    {
        $editor = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($editor)
            ->patch(route('admin.users.update', $user), [
                'role' => User::ROLE_EDITOR,
                'status' => User::STATUS_ACTIVE,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
        ]);
    }

    public function test_admin_can_change_user_role(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'role' => User::ROLE_EDITOR,
                'status' => User::STATUS_ACTIVE,
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => User::ROLE_EDITOR,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function test_admin_can_activate_pending_user(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'role' => User::ROLE_VIEWER,
                'status' => User::STATUS_ACTIVE,
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function test_admin_can_disable_active_user(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_EDITOR,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'role' => User::ROLE_EDITOR,
                'status' => User::STATUS_DISABLED,
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_DISABLED,
        ]);
    }

    public function test_admin_can_reactivate_disabled_user(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_DISABLED,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'role' => User::ROLE_VIEWER,
                'status' => User::STATUS_ACTIVE,
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function test_admin_cannot_update_themselves(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $admin), [
                'role' => User::ROLE_VIEWER,
                'status' => User::STATUS_DISABLED,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function test_user_update_requires_known_role_and_status(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'role' => 'owner',
                'status' => 'archived',
            ]);

        $response->assertSessionHasErrors(['role', 'status']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => User::ROLE_VIEWER,
            'status' => User::STATUS_PENDING,
        ]);
    }
}
