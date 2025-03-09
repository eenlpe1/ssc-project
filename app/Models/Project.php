<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'todo',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Add accessor for task count if needed
    public function getTaskCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'todo' => 'gray',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    public function updateStatusBasedOnDates()
    {
        $now = now();
        
        // Skip if already completed
        if ($this->status === 'completed') {
            return;
        }

        // Check for overdue
        if ($this->end_date < $now->startOfDay()) {
            $this->status = 'overdue';
        }
        // Check for in progress
        elseif ($this->start_date <= $now && $this->end_date >= $now) {
            $this->status = 'in_progress';
        }
        // Future projects
        elseif ($this->start_date > $now) {
            $this->status = 'todo';
        }

        if ($this->isDirty('status')) {
            $this->save();
        }
    }

    protected static function boot()
    {
        parent::boot();

        // Update status when retrieving a project
        static::retrieved(function ($project) {
            $project->updateStatusBasedOnDates();
        });
    }
} 