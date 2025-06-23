<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Supplier extends Model
{
    public $timestamps = true;
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
        $this->attributes['phone'] = strtoupper($value);
        $this->attributes['email'] = strtoupper($value);
        $this->attributes['address'] = strtoupper($value); 
    }
}
