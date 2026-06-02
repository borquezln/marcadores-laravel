<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_administration(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertTrue($admin->can('viewAny', User::class));
    }

    public function test_admin_can_update_other_users(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $this->assertTrue($admin->can('update', $user));
    }

    public function test_admin_cannot_update_themselves(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertFalse($admin->can('update', $admin));
    }

    public function test_editor_cannot_administer_users(): void
    {
        $editor = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $user = User::factory()->create();

        $this->assertFalse($editor->can('viewAny', User::class));
        $this->assertFalse($editor->can('update', $user));
    }

    public function test_viewer_cannot_administer_users(): void
    {
        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $user = User::factory()->create();

        $this->assertFalse($viewer->can('viewAny', User::class));
        $this->assertFalse($viewer->can('update', $user));
    }
}
