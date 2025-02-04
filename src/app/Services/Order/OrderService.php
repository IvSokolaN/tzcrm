<?php

namespace App\Services\Order;

use App\Enum\DeliveryScheduleType;
use App\Http\Requests\Order\StoreRequest;
use App\Models\MealPlan;
use App\Models\Order;
use App\Models\Tariff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    private Order $order;
    private Tariff $tariff;

    public function createOrder(StoreRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $userPhone = $this->getSanitizePhone($request->string('client_phone'));

            $this->tariff = Tariff::query()
                ->findOrFail($request->integer('tariff'));

            $this->order = Order::query()->create($this->prepareOrderData($request, $userPhone));

            $this->createMealPlans(
                $request->array('first_date'),
                $request->array('last_date'),
                $request->string('delivery_schedule_type')
            );
        });
    }

    /**
     * @param string $phone
     * @return string
     */
    public function getSanitizePhone(string $phone): string
    {
        return Str::replaceMatches(
            pattern: '/[^0-9]++/',
            replace: '',
            subject: $phone
        );
    }

    public function createMealPlans(array $dateFroms, array $dateTos, string $deliveryScheduleType): void
    {
        foreach ($dateFroms as $index => $dateFrom) {
            $startDate = Carbon::parse($dateFrom);
            $endDate = Carbon::parse($dateTos[$index]);

            $data = $this->generateMealPlans(
                DeliveryScheduleType::from($deliveryScheduleType),
                $startDate,
                $endDate
            );

            MealPlan::query()->insert($data);
        }
    }

    private function generateMealPlans(
        $deliveryScheduleType,
        Carbon $startDate,
        Carbon $endDate): array
    {
        return match ($deliveryScheduleType) {
            DeliveryScheduleType::EVERY_DAY => $this->getMealPlans($startDate, $endDate, 1),
            DeliveryScheduleType::EVERY_OTHER_DAY => $this->getMealPlans($startDate, $endDate, 2),
            DeliveryScheduleType::EVERY_OTHER_DAY_TWICE => $this->getEveryOtherDayTwice($startDate, $endDate),
            default => [],
        };
    }

    private function getEveryOtherDayTwice(Carbon $startDate, Carbon $endDate): array
    {
        $data = [];
        while ($startDate <= $endDate) {
            if ($startDate == $endDate) {
                // Доставляем только 1 рацион
                $data[] = $this->getDataMeals($startDate);
                break;
            } else {
                // Доставляем 2 рациона через день
                for ($i = 0; $i < 2; $i++) {
                    $data[] = $this->getDataMeals($startDate);
                }
                $startDate->addDays(2);
            }
        }

        return $data;
    }

    private function getMealPlans(Carbon $startDate, Carbon $endDate, int $addDays): array
    {
        $data = [];
        while ($startDate <= $endDate) {
            $data[] = $this->getDataMeals($startDate);
            $startDate->addDays($addDays);
        }

        return $data;
    }

    private function getDataMeals(Carbon $startDate): array
    {
        $cookingDate = $this->tariff->cooking_day_before
            ? $startDate->copy()->subDay()
            : $startDate->copy();

        return [
            'cooking_date' => $cookingDate,
            'delivery_date' => $startDate->copy(),
            'order_id' => $this->order->id,
        ];
    }

    private function prepareOrderData(StoreRequest $request, string $userPhone): array
    {
        return [
            'client_name' => $request->string('client_name'),
            'client_phone' => $userPhone,
            'schedule_type' => DeliveryScheduleType::from($request->string('delivery_schedule_type')),
            'comment' => $request->string('comment'),
            'first_date' => $request->array('first_date')[0],
            'last_date' => $request->array('last_date')[array_key_last($request->array('last_date'))],
            'tariff_id' => $this->tariff->id,
        ];
    }
}
