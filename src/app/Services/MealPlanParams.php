<?php

namespace App\Services;

use Carbon\Carbon;

final class MealPlanParams
{
    public Carbon $startDate;
    public Carbon $endDate;
    public bool $cookingDayBefore;
    public int $orderId;
    public int $addDays;

    public function __construct(
        Carbon $startDate,
        Carbon $endDate,
        bool $cookingDayBefore,
        int $orderId,
        int $addDays = 1)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->cookingDayBefore = $cookingDayBefore;
        $this->orderId = $orderId;
        $this->addDays = $addDays;
    }
}
