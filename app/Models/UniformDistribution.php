<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;

class UniformDistribution extends Model
{   
    use Notifiable, SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'student_identification_id',
        'sizes_id',
        'receipt_number',
    ];

    // public static function booted(): void
    // {

    // }
    protected $casts = [
        'sizes_id' => 'array', // or 'json'
    ];
    public function studentIdentification()
    {
        return $this->belongsTo(UniformSize::class, 'student_identification_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function setStudentNameAttribute($value)
    {
        $this->attributes['receipt_number'] = strtoupper($value);
    }
}
