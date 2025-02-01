<x-layout>
    <x-slot:title>
        Тарифы
    </x-slot>

    <div class="my-4">
        <a href="{{ route('tariffs.create') }}" class="btn btn-active btn-primary">Добавить</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название рациона</th>
                <th>Когда готовить рацион</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tariffs as $tariff)
                <tr class="hover">
                    <th>{{ $tariff->id }}</th>

                    <td>{{ $tariff->ration_name }}</td>

                    <td>{{ $tariff->cooking_day }}</td>

                    <td class="flex gap-x-4">
                        <a href="{{ route('tariffs.edit', $tariff->id) }}" class="link">Редактировать</a>

                        <form action="{{ route('tariffs.destroy', $tariff->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link link-warning">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
