<x-layout xmlns="http://www.w3.org/1999/html">
    <x-slot:title>
        Заказ - {{ $order->id }}
    </x-slot>

    <div class="flex flex-col gap-4 w-1/2">
        <table class="table table-zebra">
            <tbody>
            <tr class="hover">
                <th>Клиент</th>
                <td>{{ $order->client_name }}</td>
            </tr>

            <tr class="hover">
                <th>Номер телефона</th>
                <td>{{ $order->client_phone }}</td>
            </tr>

            <tr class="hover">
                <th>Тип расписания доставки рационов</th>
                <td>{{ $deliveryScheduleType }}</td>
            </tr>

            <tr class="hover">
                <th>Комментарий</th>
                <td>{{ $order->comment }}</td>
            </tr>

            <tr class="hover">
                <th>Начальная дата заказа</th>
                <td>{{ $order->firstDateFormat }}</td>
            </tr>

            <tr class="hover">
                <th>Конечная дата заказа</th>
                <td>{{ $order->lastDateFormat }}</td>
            </tr>

            <tr class="hover">
                <th>Тариф</th>
                <td>
                    <a href="{{ route('tariffs.edit', $tariff->id) }}"
                       class="link"
                       target="_blank">
                        {{ $tariff->ration_name }}
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="overflow-x-auto">
            <h2 class="text-2xl font-bold mb-4">Рационы Питания</h2>

            <table class="table table-zebra">
                <thead>
                <tr>
                    <th>Дата готовки</th>
                    <th>Дата доставки</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mealPlans as $mealPlan)
                    <tr class="hover">
                        <td>
                            {{ $mealPlan->cookingDateFormat }}
                        </td>

                        <td>
                            {{ $mealPlan->deliveryDateFormat }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
