<?php

namespace Tests\Unit;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkerPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_all_markers(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $marker = Marker::factory()->create();

        $this->assertTrue($admin->can('viewAny', Marker::class));
        $this->assertTrue($admin->can('create', Marker::class));
        $this->assertTrue($admin->can('update', $marker));
        $this->assertTrue($admin->can('delete', $marker));
    }

    public function test_editor_can_manage_own_markers(): void
    {
        $editor = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => $editor->id,
        ]);

        $this->assertTrue($editor->can('viewAny', Marker::class));
        $this->assertTrue($editor->can('create', Marker::class));
        $this->assertTrue($editor->can('update', $marker));
        $this->assertTrue($editor->can('delete', $marker));
    }

    public function test_editor_cannot_manage_other_users_markers(): void
    {
        $editor = User::factory()->create([
            'role' => User::ROLE_EDITOR,
        ]);

        $marker = Marker::factory()->create([
            'user_id' => User::factory()->create([
                'role' => User::ROLE_EDITOR,
            ])->id,
        ]);

        $this->assertFalse($editor->can('update', $marker));
        $this->assertFalse($editor->can('delete', $marker));
    }

    public function test_viewer_cannot_administer_markers(): void
    {
        $viewer = User::factory()->create([
            'role' => User::ROLE_VIEWER,
        ]);

        $marker = Marker::factory()->create();

        $this->assertFalse($viewer->can('viewAny', Marker::class));
        $this->assertFalse($viewer->can('create', Marker::class));
        $this->assertFalse($viewer->can('update', $marker));
        $this->assertFalse($viewer->can('delete', $marker));
        $this->assertTrue($viewer->can('view', $marker));
    }
}
