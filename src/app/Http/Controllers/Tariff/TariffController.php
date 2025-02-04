<?php

namespace App\Http\Controllers\Tariff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tariff\StoreRequest;
use App\Http\Requests\Tariff\UpdateRequest;
use App\Models\Tariff;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class TariffController extends Controller
{
    /**
     * @return Application|View|Factory
     */
    public function index(): Application|View|Factory
    {
        $tariffs = Tariff::all();

        return view('tariffs.index', compact('tariffs'));
    }

    /**
     * @return Application|View|Factory
     */
    public function create(): Application|View|Factory
    {
        return view('tariffs.create');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        Tariff::query()
            ->create($request->validated());

        return redirect()->route('tariffs.index');
    }

    /**
     * @param Tariff $tariff
     * @return Application|View|Factory
     */
    public function edit(Tariff $tariff): Application|View|Factory
    {
        return view('tariffs.edit', compact('tariff'));
    }

    /**
     * @param Tariff $tariff
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Tariff $tariff, UpdateRequest $request): RedirectResponse
    {
        $tariff->update($request->validated());

        return redirect()->route('tariffs.index');
    }

    /**
     * @param Tariff $tariff
     * @return RedirectResponse
     */
    public function destroy(Tariff $tariff): RedirectResponse
    {
        $tariff->delete();

        return redirect()->route('tariffs.index');
    }
}
