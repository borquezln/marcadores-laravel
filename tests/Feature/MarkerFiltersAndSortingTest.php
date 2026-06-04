<?php

namespace Tests\Feature;

use App\Models\Marker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkerFiltersAndSortingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $this->editor = User::factory()->create(['role' => 'editor', 'status' => 'active']);
    }

    public function test_can_filter_by_type(): void
    {
        Marker::factory()->create(['type' => Marker::TYPE_PLACE, 'title' => 'A Place']);
        Marker::factory()->create(['type' => Marker::TYPE_BILLBOARD, 'title' => 'A Billboard']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['type' => Marker::TYPE_PLACE]));

        $response->assertStatus(200);
        $response->assertSee('A Place');
        $response->assertDontSee('A Billboard');
    }

    public function test_can_filter_by_status(): void
    {
        Marker::factory()->create(['status' => Marker::STATUS_ACTIVE, 'title' => 'Active Marker']);
        Marker::factory()->create(['status' => Marker::STATUS_INACTIVE, 'title' => 'Inactive Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['status' => Marker::STATUS_ACTIVE]));

        $response->assertStatus(200);
        $response->assertSee('Active Marker');
        $response->assertDontSee('Inactive Marker');
    }

    public function test_can_filter_by_my_markers(): void
    {
        Marker::factory()->create(['user_id' => $this->admin->id, 'title' => 'Admin Marker']);
        Marker::factory()->create(['user_id' => $this->editor->id, 'title' => 'Editor Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['my_markers' => '1']));

        $response->assertStatus(200);
        $response->assertSee('Admin Marker');
        $response->assertDontSee('Editor Marker');

        $response = $this->actingAs($this->editor)
            ->get(route('markers.index', ['my_markers' => '1']));

        $response->assertStatus(200);
        $response->assertSee('Editor Marker');
        $response->assertDontSee('Admin Marker');
    }

    public function test_can_sort_markers_by_title(): void
    {
        Marker::factory()->create(['title' => 'B Marker']);
        Marker::factory()->create(['title' => 'A Marker']);
        Marker::factory()->create(['title' => 'C Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['sort_by' => 'title_asc']));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['A Marker', 'B Marker', 'C Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['sort_by' => 'title_desc']));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['C Marker', 'B Marker', 'A Marker']);
    }

    public function test_can_sort_markers_by_owner(): void
    {
        $userA = User::factory()->create(['name' => 'Alice']);
        $userB = User::factory()->create(['name' => 'Bob']);

        Marker::factory()->create(['user_id' => $userA->id, 'title' => 'Alice Marker']);
        Marker::factory()->create(['user_id' => $userB->id, 'title' => 'Bob Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['sort_by' => 'owner_asc']));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Alice Marker', 'Bob Marker']);

        $response = $this->actingAs($this->admin)
            ->get(route('markers.index', ['sort_by' => 'owner_desc']));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Bob Marker', 'Alice Marker']);
    }
}
