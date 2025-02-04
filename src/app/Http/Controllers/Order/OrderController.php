<?php

namespace App\Http\Controllers\Order;

use App\Enum\DeliveryScheduleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\Models\Tariff;
use App\Services\Order\OrderService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
     * @param OrderService $orderService
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, OrderService $orderService): RedirectResponse
    {
//        dd($request->all());

        $orderService->createOrder($request);

        return redirect()->route('orders.index');
    }

    /**
     * @param Order $order
     * @return View|Application|Factory
     */
    public function show(Order $order): View|Application|Factory
    {
        $deliveryScheduleType = __("delivery_schedule.{$order->schedule_type->value}");
        $mealPlans = $order->mealPlans;
        $tariff = $order->tariff;

        return view('orders.show', compact(
            'order',
            'deliveryScheduleType',
            'mealPlans',
            'tariff'
        ));
    }
}
