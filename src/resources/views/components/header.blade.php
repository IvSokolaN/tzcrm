<div class="navbar bg-base-100">
    <div class="navbar-start">
        <a href="/" class="btn btn-ghost text-xl">
            {{ config('app.name') }}
        </a>
    </div>
    <div class="navbar-center lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li>
                <a href="/">
                    Главная
                </a>
            </li>

            <li>
                <a href="{{ route('tariffs.index') }}">
                    Тарифы
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar-end">
    </div>
</div>
