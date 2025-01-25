<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'total_stars',
        'nickname',
        'department',
        'position'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $attributes = [
        'role' => 'user',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdviser(): bool
    {
        return $this->role === 'adviser';
    }

    public function canCreateProjects(): bool
    {
        return in_array($this->role, ['admin', 'adviser']);
    }

    public function canCreateTasks(): bool
    {
        return in_array($this->role, ['admin', 'adviser']);
    }

    public function canRateTasks(): bool
    {
        return in_array($this->role, ['admin', 'adviser']);
    }

    public function canCreateUsers(): bool
    {
        return in_array($this->role, ['admin', 'adviser']);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function getTaskCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getProjectCountAttribute()
    {
        return $this->tasks()->distinct('project_id')->count('project_id');
    }

    public function getStarsAttribute()
    {
        return $this->tasks()
            ->where('status', 'completed')
            ->whereNotNull('rating')
            ->sum('rating');
    }
}
