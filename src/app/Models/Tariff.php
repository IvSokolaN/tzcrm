<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'ration_name',
        'cooking_day_before',
    ];

    public function getCookingDayAttribute(): string
    {
        return $this->cooking_day_before ? 'за день до доставки' : 'в день доставки';
    }
}
