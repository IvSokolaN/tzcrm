<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlan extends Model
{
    protected $fillable = [
        'cooking_date',
        'delivery_date',
        'order_id',
    ];

    protected $casts = [
        'cooking_date' => 'date',
        'delivery_date' => 'date',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return string
     */
    public function getCookingDateFormatAttribute(): string
    {
        return $this->cooking_date->translatedFormat('d F Y');
    }

    /**
     * @return string
     */
    public function getDeliveryDateFormatAttribute(): string
    {
        return $this->delivery_date->translatedFormat('d F Y');
    }
}
