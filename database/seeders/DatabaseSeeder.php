<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $budi = User::updateOrCreate(
            ['email' => 'budi@klikban.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
            ]
        );

        $siti = User::updateOrCreate(
            ['email' => 'siti@klikban.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
            ]
        );

        $rizky = User::updateOrCreate(
            ['email' => 'rizky@klikban.com'],
            [
                'name' => 'Rizky Pratama',
                'password' => Hash::make('password'),
            ]
        );

        $dewi = User::updateOrCreate(
            ['email' => 'dewi@klikban.com'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Workspace (PT WUNAWAN) with Budi as Owner / Admin
        $workspace = Workspace::updateOrCreate(
            ['slug' => 'pt-wunawan'],
            [
                'name' => 'PT WUNAWAN',
                'owner_id' => $budi->id,
                'description' => 'Workspace Kolaborasi PT WUNAWAN',
                'invite_code' => 'WUNAWAN1',
            ]
        );

        // Attach members
        $workspace->members()->syncWithoutDetaching([
            $budi->id => ['role' => 'owner'],
            $siti->id => ['role' => 'admin'],
            $rizky->id => ['role' => 'member'],
            $dewi->id => ['role' => 'member'],
        ]);

        // 3. Create Project (Proyek Akhir RPL)
        $project = Project::updateOrCreate(
            ['slug' => 'proyek-akhir-rpl', 'workspace_id' => $workspace->id],
            [
                'name' => 'Proyek Akhir RPL',
                'description' => 'Pengembangan Sistem Kolaboratif Task Management',
                'created_by' => $budi->id,
            ]
        );

        // 4. Create Tasks matching Image 1
        Task::where('project_id', $project->id)->delete();

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => 'Finalisasi Arsitektur Sistem',
            'description' => 'Menentukan tech stack dan diagram ERD untuk modul inti.',
            'priority' => 'didahulukan', // Merah
            'status' => 'todo',
            'due_date' => '2023-10-24',
            'assignee_name' => 'Budi Santoso',
            'assigned_to' => $budi->id,
            'order' => 1,
            'created_by' => $budi->id,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => 'Mockup UI Dashboard',
            'description' => 'Desain fidelity tinggi untuk workspace dan navigasi utama.',
            'priority' => 'perlu_diperhatikan', // Kuning/Oranye
            'status' => 'todo',
            'due_date' => '2023-10-26',
            'assignee_name' => 'Siti Aminah',
            'assigned_to' => $siti->id,
            'order' => 2,
            'created_by' => $siti->id,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => 'Setup Repository GitHub',
            'description' => 'Inisialisasi repo, branching strategy, dan CI/CD awal.',
            'priority' => 'eksternal', // Biru
            'status' => 'in_progress',
            'due_date' => '2023-10-23',
            'assignee_name' => 'Rizky Pratama',
            'assigned_to' => $rizky->id,
            'order' => 1,
            'created_by' => $rizky->id,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => 'Draft Dokumen SRS',
            'description' => 'Penyusunan requirement specification bab 1-3.',
            'priority' => 'biasa', // Hijau
            'status' => 'review',
            'due_date' => '2023-10-25',
            'assignee_name' => 'Dewi Lestari',
            'assigned_to' => $dewi->id,
            'order' => 1,
            'created_by' => $dewi->id,
        ]);

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => 'Pembentukan Tim',
            'description' => 'Menentukan pembagian peran PO, Scrum Master, dan Dev.',
            'priority' => 'biasa',
            'status' => 'done',
            'due_date' => '2023-10-20',
            'assignee_name' => 'Seluruh Tim',
            'assigned_to' => null,
            'order' => 1,
            'created_by' => $budi->id,
        ]);

        // 5. Create Activity Logs matching Image 3
        ActivityLog::where('workspace_id', $workspace->id)->delete();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        ActivityLog::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'user_id' => $siti->id,
            'user_name' => 'Siti Aminah',
            'action_type' => 'new_task',
            'badge_text' => 'NEW TASK',
            'badge_color' => 'green',
            'description' => 'Siti Aminah menambahkan tugas baru "Finalisasi ERD"',
            'created_at' => $today->copy()->setHour(10)->setMinute(45),
        ]);

        ActivityLog::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'user_id' => $budi->id,
            'user_name' => 'Budi Santoso',
            'action_type' => 'status_update',
            'badge_text' => 'STATUS UPDATE',
            'badge_color' => 'blue',
            'description' => 'Budi Santoso memindahkan tugas "Setup Database" ke In Progress',
            'created_at' => $today->copy()->setHour(9)->setMinute(12),
        ]);

        ActivityLog::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'user_id' => $budi->id,
            'user_name' => 'Budi Santoso',
            'action_type' => 'member_joined',
            'badge_text' => 'MEMBER JOINED',
            'badge_color' => 'orange',
            'description' => 'Admin mengundang Rina ke dalam workspace',
            'created_at' => $today->copy()->setHour(8)->setMinute(30),
        ]);

        ActivityLog::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'user_id' => null,
            'user_name' => 'Sistem',
            'action_type' => 'priority_change',
            'badge_text' => 'PRIORITY CHANGE',
            'badge_color' => 'red',
            'description' => 'Sistem menandai tugas "Fix Login Bug" sebagai Didahulukan',
            'created_at' => $yesterday->copy()->setHour(16)->setMinute(45),
        ]);
    }
}
