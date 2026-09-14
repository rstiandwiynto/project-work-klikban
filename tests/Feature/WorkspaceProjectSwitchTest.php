<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkspaceProjectSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_and_project_creation_and_switching(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user);

        // 2. Create Workspace A
        $responseWsA = $this->post(route('workspaces.store'), [
            'name' => 'Workspace A',
            'description' => 'Deskripsi Workspace A',
        ]);
        $responseWsA->assertRedirect(route('board'));

        $workspaceA = Workspace::where('name', 'Workspace A')->first();
        $this->assertNotNull($workspaceA);
        $this->assertEquals($workspaceA->id, session('active_workspace_id'));

        // 3. Create Project A inside Workspace A
        $responseProjA = $this->post(route('projects.store'), [
            'name' => 'Proyek Alpha',
            'description' => 'Proyek Alpha di WS A',
        ]);
        $responseProjA->assertRedirect();

        $projectAlpha = Project::where('name', 'Proyek Alpha')->where('workspace_id', $workspaceA->id)->first();
        $this->assertNotNull($projectAlpha);
        $this->assertEquals($projectAlpha->id, session('active_project_id'));

        // 4. Create Workspace B
        $responseWsB = $this->post(route('workspaces.store'), [
            'name' => 'Workspace B',
            'description' => 'Deskripsi Workspace B',
        ]);
        $responseWsB->assertRedirect(route('board'));

        $workspaceB = Workspace::where('name', 'Workspace B')->first();
        $this->assertNotNull($workspaceB);
        $this->assertEquals($workspaceB->id, session('active_workspace_id'));

        // 5. Verify Workspace A and Project Alpha are NOT deleted/lost
        $this->assertDatabaseHas('workspaces', ['id' => $workspaceA->id, 'name' => 'Workspace A']);
        $this->assertDatabaseHas('projects', ['id' => $projectAlpha->id, 'name' => 'Proyek Alpha', 'workspace_id' => $workspaceA->id]);

        // 6. Switch back to Workspace A
        $switchResponse = $this->get(route('workspaces.switch', $workspaceA->id));
        $switchResponse->assertRedirect();
        $this->assertEquals($workspaceA->id, session('active_workspace_id'));

        // 7. Switch to Project Alpha
        $switchProjResponse = $this->get(route('projects.switch', $projectAlpha->id));
        $switchProjResponse->assertRedirect();
        $this->assertEquals($projectAlpha->id, session('active_project_id'));
        $this->assertEquals($workspaceA->id, session('active_workspace_id'));

        // 8. Verify board loads with correct data
        $boardResponse = $this->get(route('board'));
        $boardResponse->assertStatus(200);
        $boardResponse->assertSee('Workspace A');
        $boardResponse->assertSee('Proyek Alpha');
    }
}
