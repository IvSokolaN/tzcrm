<x-layout>
    <x-slot:title>
        Тарифы - Создание
    </x-slot>

    <div class="card bg-base-100 w-full shadow-xl">
        <form action="{{ route('tariffs.store') }}"
              method="POST"
              class="card-body w-1/3">
            @csrf

            <div class="label">
                <span class="label-text">Название рациона</span>
            </div>
            <input type="text"
                   placeholder="Название рациона"
                   class="input input-bordered w-full"
                   name="ration_name"
                   value="{{ old('ration_name') }}"
            />
            @error('ration_name')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <div class="form-control">
                <label class="label cursor-pointer">
                    <input type="radio"
                           name="cooking_day_before"
                           class="radio checked:bg-red-500"
                           value="1" {{ old('cooking_day_before') == 1 ? 'checked' : '' }}/>
                    <span class="label-text">готовить рацион за день до доставки</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label cursor-pointer">
                    <input type="radio"
                           name="cooking_day_before"
                           class="radio checked:bg-blue-500"
                           value="0" {{ old('cooking_day_before') == 0 ? 'checked' : '' }}/>
                    <span class="label-text">готовить рацион в день доставки</span>
                </label>
            </div>

            @error('cooking_day_before')
            <span class="text-red-500">
                {{ $message }}
            </span>
            @enderror

            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
</x-layout>
