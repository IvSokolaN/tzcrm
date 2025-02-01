<x-layout>
    <x-slot:title>
        Заказы
    </x-slot>

    <div class="my-4">
        <a href="{{ route('orders.create') }}" class="btn btn-active btn-primary">Создать заказ</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr class="hover">
                    <th>{{ $order->id }}</th>

                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="link">
                            {{ $order->client_name }}
                        </a>
                    </td>

                    <td>{{ $order->client_phone }}</td>

                    <td class="flex gap-x-4">
                        <a href="{{ route('orders.show', $order->id) }}" class="link">Посмотреть</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
