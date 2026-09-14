<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'project_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'assignee_name',
        'assigned_to',
        'order',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Priority Label matching design
     */
    public function getPriorityLabelAttribute(): string
    {
        if ($this->status === 'done') {
            return 'Completed';
        }

        return match ($this->priority) {
            'didahulukan' => 'Critical',
            'perlu_diperhatikan' => 'High',
            'eksternal' => 'Medium',
            'biasa' => 'Low',
            default => 'Medium',
        };
    }

    /**
     * Priority short badge code / description
     */
    public function getPriorityDescriptionAttribute(): string
    {
        return match ($this->priority) {
            'didahulukan' => 'Tugas yang didahulukan (Critical / Merah)',
            'perlu_diperhatikan' => 'Tugas yang perlu diperhatikan (High / Kuning)',
            'eksternal' => 'Tugas eksternal atau tambahan (Medium / Biru)',
            'biasa' => 'Tugas yang biasa dikerjakan (Low / Hijau)',
            default => 'Tugas biasa',
        };
    }

    /**
     * Priority color classes for card border left
     */
    public function getPriorityBorderClassAttribute(): string
    {
        if ($this->status === 'done') {
            return 'border-l-4 border-l-success';
        }

        return match ($this->priority) {
            'didahulukan' => 'border-l-4 border-l-accent',
            'perlu_diperhatikan' => 'border-l-4 border-l-amber-600',
            'eksternal' => 'border-l-4 border-l-primary',
            'biasa' => 'border-l-4 border-l-success',
            default => 'border-l-4 border-l-borderc',
        };
    }

    /**
     * Priority badge CSS styles
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        if ($this->status === 'done') {
            return 'text-success bg-emerald-50';
        }

        return match ($this->priority) {
            'didahulukan' => 'text-accent bg-red-50',
            'perlu_diperhatikan' => 'text-amber-700 bg-amber-50',
            'eksternal' => 'text-primary bg-blue-50',
            'biasa' => 'text-success bg-emerald-50',
            default => 'text-textsub bg-slate-100',
        };
    }

    /**
     * Formatted due date string (e.g. "24 Okt 2023")
     */
    public function getFormattedDueDateAttribute(): ?string
    {
        if (!$this->due_date) {
            return null;
        }

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $dt = Carbon::parse($this->due_date);
        return $dt->day . ' ' . ($months[$dt->month] ?? $dt->format('M')) . ' ' . $dt->year;
    }

    /**
     * Status label in Indonesian
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'todo' => 'To-Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
            default => ucfirst($this->status),
        };
    }
}
