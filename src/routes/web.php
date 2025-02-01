<?php

use App\Http\Controllers\Tariff\TariffController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('main');
});

Route::prefix('tariffs')->group(function () {
    Route::get('/', [TariffController::class, 'index'])->name('tariffs.index');
    Route::get('/create', [TariffController::class, 'create'])->name('tariffs.create');
    Route::post('/', [TariffController::class, 'store'])->name('tariffs.store');
    Route::get('/{tariff:id}/edit', [TariffController::class, 'edit'])->name('tariffs.edit');
    Route::patch('/{tariff:id}', [TariffController::class, 'update'])->name('tariffs.update');
    Route::delete('/{tariff:id}', [TariffController::class, 'destroy'])->name('tariffs.destroy');
});
