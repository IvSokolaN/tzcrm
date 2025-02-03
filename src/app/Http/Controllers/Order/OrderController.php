<?php

namespace App\Http\Controllers\Order;

use App\Enum\DeliveryScheduleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\MealPlan;
use App\Models\Order;
use App\Models\Tariff;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * @return Application|View|Factory
     */
    public function index(): Application|View|Factory
    {
        $orders = Order::all();

        return view('orders.index', compact('orders'));
    }

    /**
     * @return Application|View|Factory
     */
    public function create(): Application|View|Factory
    {
        $tariffs = Tariff::all();
        $deliveryScheduleTypes = DeliveryScheduleType::values();

        return view('orders.create', compact(
            'tariffs',
            'deliveryScheduleTypes',
        ));
    }

    public function store(StoreRequest $request)
    {
        // В first_date и last_date записывается самая ранняя и самая поздняя дата доставки среди созданных рационов питания.
        // TODO сделать статичный список дат

        $deliveryScheduleType = DeliveryScheduleType::from($request->string('delivery_schedule_type'));

        $tariff = Tariff::query()
            ->findOrFail($request->integer('tariff'));

        $userPhone = Str::replaceMatches(
            pattern: '/[^A-Za-z0-9]++/',
            replace: '',
            subject: $request->string('client_phone')
        );
        $firstDate = $request->date('first_date');
        $lastDate = $request->date('last_date');

        DB::transaction(function () use (
            $deliveryScheduleType,
            $tariff,
            $userPhone,
            $firstDate,
            $lastDate,
            $request
        ) {
            $order = Order::query()
                ->create([
                    'client_name' => $request->string('client_name'),
                    'client_phone' => $userPhone,
                    'schedule_type' => $deliveryScheduleType,
                    'comment' => $request->comment,
                    'first_date' => $firstDate,
                    'last_date' => $lastDate,
                    'tariff_id' => $request->integer('tariff'),
                ]);

            $startDate = $request->date('first_date');
            $endDate = $request->date('last_date');
            $data = $this->generateMealPlans(
                $deliveryScheduleType,
                $startDate,
                $endDate,
                $tariff->cooking_day_before,
                $order->id);

            MealPlan::query()->insert($data);
        });

        dd($request->all(),
            $deliveryScheduleType,
            $tariff->cooking_day_before,
        );
    }

    private function generateMealPlans(
        $deliveryScheduleType,
        Carbon $startDate,
        Carbon $endDate,
        bool $cooking_day_before,
        int $orderId): array
    {
        $data = [];
        return match ($deliveryScheduleType) {
            DeliveryScheduleType::EVERY_DAY => $this->getMealPlans($startDate, $endDate, $cooking_day_before, 1, $orderId),
            DeliveryScheduleType::EVERY_OTHER_DAY => $this->getMealPlans($startDate, $endDate, $cooking_day_before, 2, $orderId),
            DeliveryScheduleType::EVERY_OTHER_DAY_TWICE => $this->getEveryOtherDayTwice($startDate, $endDate, $cooking_day_before, $orderId),
            default => $data,
        };
    }

    private function getEveryOtherDayTwice(Carbon $startDate, Carbon $endDate, bool $cooking_day_before, int $orderId): array
    {
        $data = [];
        while ($startDate <= $endDate) {
            if ($startDate == $endDate) {
                // Доставляем только 1 рацион
                $data[] = $this->getDataMeals($startDate, $cooking_day_before, $orderId);
                break;
            } else {
                // Доставляем 2 рациона через день
                for ($i = 0; $i < 2; $i++) {
                    $data[] = $this->getDataMeals($startDate, $cooking_day_before, $orderId);
                }
                $startDate->addDays(2);
            }
        }

        return $data;
    }

    private function getMealPlans(Carbon $startDate, Carbon $endDate, bool $cooking_day_before, int $addDays, int $orderId): array
    {
        $data = [];
        while ($startDate <= $endDate) {
            $data[] = $this->getDataMeals($startDate, $cooking_day_before, $orderId);
            $startDate->addDays($addDays);
        }

        return $data;
    }

    private function getDataMeals(Carbon $startDate, bool $cooking_day_before, int $orderId): array
    {
        $cookingDate = $cooking_day_before
            ? $startDate->copy()->subDay()
            : $startDate->copy();

        return [
            'cooking_date' => $cookingDate,
            'delivery_date' => $startDate->copy(),
            'order_id' => $orderId,
        ];
    }

    /**
     * @param Order $order
     * @return View|Application|Factory
     */
    public function show(Order $order): View|Application|Factory
    {
        return view('orders.show', compact('order'));
    }
}
