<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){
    Route::prefix('login')->group(function(){
        Route::get('', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('', [LoginController::class, 'login']);
    });

    Route::middleware('admin.auth')->name('admin.')->group(function(){
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('notes')->controller(\App\Http\Controllers\Admin\NoteController::class)
            ->group(function () {
                Route::get('', 'index')->name('notes');
        });
    });
});

Route::get('admin/{any?}', function ($any = null) {
    return redirect()->route('admin.dashboard');
})->where('any', '.*');
