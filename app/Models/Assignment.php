<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'report_id',
    'officer_id',
    'assigned_at'
])]
class Assignment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
