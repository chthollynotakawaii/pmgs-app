<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = ['name', 'unit', 'stock'];

    public function requests()
    {
        return $this->hasMany(ConsumableRequest::class);
    }
}

