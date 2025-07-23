<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableRequest extends Model
{
    protected $fillable = ['consumable_id', 'requested_by', 'quantity', 'purpose', 'requested_at'];

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
