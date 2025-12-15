<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'instructor',
        'category',
        'duration',
        'price',
    ];

    /**
     * Get the users enrolled in this course.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
