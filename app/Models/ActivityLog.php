<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // We use created_at

    protected $fillable = [
        'workspace_id',
        'project_id',
        'user_id',
        'user_name',
        'action_type',
        'badge_text',
        'badge_color',
        'description',
        'details',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->created_at)) {
                $log->created_at = Carbon::now();
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to log an activity with standard parameters
     */
    public static function log(
        int $workspaceId,
        string $description,
        string $badgeText,
        string $badgeColor = 'blue',
        string $actionType = 'status_update',
        ?int $projectId = null,
        ?int $userId = null,
        ?string $userName = null,
        ?array $details = null
    ): self {
        return self::create([
            'workspace_id' => $workspaceId,
            'project_id' => $projectId,
            'user_id' => $userId ?? auth()->id(),
            'user_name' => $userName ?? (auth()->user()?->name ?? 'Sistem'),
            'action_type' => $actionType,
            'badge_text' => $badgeText,
            'badge_color' => $badgeColor,
            'description' => $description,
            'details' => $details,
            'created_at' => Carbon::now(),
        ]);
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('H:i') : '--:--';
    }

    public function getDateGroupAttribute(): string
    {
        if (!$this->created_at) return 'LAINNYA';

        $today = Carbon::today();
        $created = $this->created_at->copy()->startOfDay();

        if ($created->equalTo($today)) {
            return 'HARI INI';
        } elseif ($created->equalTo($today->copy()->subDay())) {
            return 'KEMARIN';
        }

        $months = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        return $this->created_at->day . ' ' . ($months[$this->created_at->month] ?? $this->created_at->format('M')) . ' ' . $this->created_at->year;
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->badge_color) {
            'green', 'emerald' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
            'blue' => 'bg-blue-50 text-blue-600 border border-blue-200',
            'orange', 'amber' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'red', 'rose' => 'bg-rose-50 text-rose-600 border border-rose-200',
            'purple', 'violet' => 'bg-purple-50 text-purple-600 border border-purple-200',
            default => 'bg-gray-50 text-gray-600 border border-gray-200',
        };
    }

    public function getUserInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->user_name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->user_name, 0, 2));
    }
}
