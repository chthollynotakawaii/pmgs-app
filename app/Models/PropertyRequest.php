<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    protected $fillable = [
    'category_id',
    'user_id',
    'quantity',
    'purpose',
    'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
