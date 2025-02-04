<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>

<body>
<div class="container mx-auto p-10">
    <x-header/>

    <main>
        <h1 class="text-center text-3xl font-bold">
            {{ $title ?? '' }}
        </h1>

        <div>
            {{ $slot }}
        </div>
    </main>
</div>

@yield('popup')
@yield('scripts')

@vite('resources/js/app.js')
</body>
</html>
