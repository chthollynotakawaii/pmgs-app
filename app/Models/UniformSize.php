<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UniformSize extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_name',
        'student_identification',
        'department_id',
        'course_id',
        'sizes',
    ];

    protected $casts = [
        'sizes' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = strtoupper($value);
    }
}