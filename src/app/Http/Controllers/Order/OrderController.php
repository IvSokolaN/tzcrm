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
use Illuminate\Http\RedirectResponse;
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

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $tariff = Tariff::query()
            ->findOrFail($request->integer('tariff'));

        DB::transaction(function () use ($tariff, $request) {
            $dateFroms = $request->array('first_date');
            $dateTos = $request->array('last_date');
            $firstDate = $dateFroms[0];
            $lastDate = $dateTos[array_key_last($dateTos)];
            $deliveryScheduleType = DeliveryScheduleType::from($request->string('delivery_schedule_type'));
            $userPhone = Str::replaceMatches(
                pattern: '/[^0-9]++/',
                replace: '',
                subject: $request->string('client_phone')
            );

            $order = Order::query()
                ->create([
                    'client_name' => $request->string('client_name'),
                    'client_phone' => $userPhone,
                    'schedule_type' => $deliveryScheduleType,
                    'comment' => $request->string('comment'),
                    'first_date' => $firstDate,
                    'last_date' => $lastDate,
                    'tariff_id' => $request->integer('tariff'),
                ]);

            foreach ($dateFroms as $index => $dateFrom) {
                $startDate = Carbon::parse($dateFrom);
                $endDate = Carbon::parse($dateTos[$index]);

                $data = $this->generateMealPlans(
                    $deliveryScheduleType,
                    $startDate,
                    $endDate,
                    $tariff->cooking_day_before,
                    $order->id
                );

                MealPlan::query()->insert($data);
            }
        });

        return redirect()->route('orders.index');
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
