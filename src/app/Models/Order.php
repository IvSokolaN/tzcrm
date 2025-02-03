<?php

namespace App\Models;

use App\Enum\DeliveryScheduleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_phone',
        'schedule_type',
        'comment',
        'first_date',
        'last_date',
        'tariff_id',
    ];

    protected $casts = [
        'schedule_type' => DeliveryScheduleType::class,
        'first_date' => 'date',
        'last_date' => 'date',
    ];

    /**
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }


}
