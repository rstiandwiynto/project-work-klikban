<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'description',
        'invite_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workspace) {
            if (empty($workspace->slug)) {
                $workspace->slug = Str::slug($workspace->name) . '-' . Str::random(5);
            }
            if (empty($workspace->invite_code)) {
                $workspace->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if a user is Admin or Owner of this workspace
     */
    public function isAdmin(?User $user): bool
    {
        if (!$user) return false;
        if ($this->owner_id === $user->id) return true;

        $member = $this->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->pivot->role, ['owner', 'admin']);
    }

    /**
     * Check if a user has access (is owner or invited member)
     */
    public function hasMember(?User $user): bool
    {
        if (!$user) return false;
        if ($this->owner_id === $user->id) return true;

        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Full shareable invitation link
     */
    public function getInviteUrlAttribute(): string
    {
        return url('/workspaces/join-link/' . $this->invite_code);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 1));
    }
}
