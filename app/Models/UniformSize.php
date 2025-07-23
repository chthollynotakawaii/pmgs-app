<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function uniformDistribution(): HasOne
    {
        return $this->hasOne(UniformDistribution::class, 'student_name_id');
    }
    public function uniformInventories()
    {
        return $this->hasMany(UniformInventory::class);
    }
    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = strtoupper($value);
    }
}
