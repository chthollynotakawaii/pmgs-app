<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Location extends Model
{
    public $timestamps = true;
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
    ];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
