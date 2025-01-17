<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'assigned_to',
        'status',
        'start_date',
        'end_date',
        'rating'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'todo',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
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
        // Future tasks
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
        
        static::creating(function ($task) {
            if (!$task->status) {
                $task->status = 'todo';
            }
        });

        static::created(function ($task) {
            $task->updateStatusBasedOnDates();
        });
    }
} 