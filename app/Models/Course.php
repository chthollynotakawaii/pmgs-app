<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Course extends Model
{
    use HasFactory, Notifiable;
        protected $fillable = [
        'name',
    ];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
    public function uniformInventories()
    {
        return $this->hasMany(UniformInventory::class);
    }

}
