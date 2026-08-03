<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    protected $fillable = [
        'label',
        'seats',
        'shape',
        'zone',
        'pos_x',
        'pos_y',
        'sort',
    ];

    protected $casts = [
        'seats' => 'integer',
        'pos_x' => 'integer',
        'pos_y' => 'integer',
        'sort' => 'integer',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}
