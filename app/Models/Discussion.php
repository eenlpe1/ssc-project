<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'title',
        'location',
        'date',
        'description'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function messages()
    {
        return $this->hasMany(DiscussionMessage::class);
    }
} 