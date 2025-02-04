<?php

namespace App\Enum;

enum DeliveryScheduleType:string
{
    case EVERY_DAY = 'EVERY_DAY';
    case EVERY_OTHER_DAY = 'EVERY_OTHER_DAY';
    case EVERY_OTHER_DAY_TWICE = 'EVERY_OTHER_DAY_TWICE';

    public static function values(): array {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function localizedValues(): array {
        return array_map(fn($case) => __("delivery_schedule.{$case->value}"), self::cases());
    }
}
