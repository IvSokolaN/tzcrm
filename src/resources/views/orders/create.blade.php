<x-layout>
    <x-slot:title>
        Заказы - Создание
    </x-slot>

    <div class="card bg-base-100 w-full shadow-xl">
        <form action="{{ route('orders.store') }}"
              method="POST"
              class="card-body w-1/3">
            @csrf

            <div class="label">
                <span class="label-text">Имя</span>
            </div>
            <input type="text"
                   placeholder="Имя"
                   class="input input-bordered w-full"
                   name="client_name"
                   value="{{ old('client_name') }}"
            />
            @error('client_name')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="label">
                <span class="label-text">Номер телефона</span>
            </div>
            <input type="tel"
                   placeholder="Номер телефона"
                   class="input input-bordered w-full"
                   name="client_phone"
                   value="{{ old('client_phone') }}"
            />
            @error('client_phone')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="label">
                <span class="label-text">Тариф</span>
            </div>
            <label class="form-control w-full max-w-xs">
                <select class="select select-bordered"
                        name="tariff">
                    <option disabled selected>Выберите тариф</option>
                    @foreach($tariffs as $tariff)
                        <option value="{{ $tariff->id }}"
                            {{ old('tariff_id') == $tariff->id ? 'selected' : '' }}>
                            {{ $tariff->ration_name }}
                        </option>
                    @endforeach
                </select>
            </label>
            @error('tariff')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="label">
                <span class="label-text">Тип расписания доставки рационов</span>
            </div>
            <label class="form-control w-full max-w-xs">
                <select class="select select-bordered"
                        name="delivery_schedule_type">
                    <option disabled selected>Тип расписания доставки рационов</option>
                    @foreach ($deliveryScheduleTypes as $deliveryScheduleType)
                        <option
                            value="{{ $deliveryScheduleType }}">{{ __('delivery_schedule.' . $deliveryScheduleType) }}</option>
                    @endforeach
                </select>
            </label>
            @error('delivery_schedule_type')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <label class="form-control">
                <span class="label label-text">Комментарий к заказу</span>
                <textarea class="textarea textarea-bordered h-24"
                          placeholder="Комментарий к заказу"
                          name="comment">{{ old('comment') }}</textarea>
            </label>
            @error('comment')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="label">
                <span class="label-text">Дата от</span>
            </div>
            <input type="date"
                   placeholder="Дата от"
                   class="input input-bordered w-full"
                   name="first_date"
                   value="{{ old('first_date') }}"
            />
            @error('first_date')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="label">
                <span class="label-text">Дата до</span>
            </div>
            <input type="date"
                   placeholder="Дата до"
                   class="input input-bordered w-full"
                   name="last_date"
                   value="{{ old('last_date') }}"
            />
            @error('last_date')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <button type="submit" class="btn btn-primary mt-4">Создать</button>
        </form>
    </div>
</x-layout>
