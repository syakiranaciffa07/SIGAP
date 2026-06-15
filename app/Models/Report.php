<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'type',
    'title',
    'description',
    'latitude',
    'longitude',
    'photo',
    'water_level',
    'status',
    'priority_score',
    'duplicate_count',
    'upvote_count'
])]
class Report extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function upvotes()
    {
        return $this->hasMany(Upvote::class);
    }

    public function isUpvotedByUser($userId): bool
    {
        if (!$userId) return false;
        return $this->upvotes()->where('user_id', $userId)->exists();
    }
}
