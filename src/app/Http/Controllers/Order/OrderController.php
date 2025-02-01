<?php

namespace App\Http\Controllers\Order;

use App\Enum\DeliveryScheduleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\Models\Tariff;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

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

    public function store(StoreRequest $request): void
    {
        $deliveryScheduleType = DeliveryScheduleType::from($request->validated()['delivery_schedule_type']);

        Order::query()
            ->create([

            ]);
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
