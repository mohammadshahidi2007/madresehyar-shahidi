<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class)
            ->withPivot(['enrolled_on', 'left_on', 'status'])
            ->withTimestamps();
    }
}
