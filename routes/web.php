<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('', [WebsiteController::class, 'index'])->name('home');
Route::post('load-more', [WebsiteController::class, 'loadMore'])->name('load_more');
Route::middleware('auth')->group(function () {
    Route::prefix('notepad')->controller(NoteController::class)
        ->name('notepad')->group(function () {
        Route::get('', 'index');
        Route::get('items', 'loadItems')->name('.items');
        Route::post('store', 'store')->name('.store');
        Route::post('import', 'import')->name('.import');
        Route::post('download/{key}/{extension}', 'download')->name('.download');
        Route::get('view/{key}', 'view')->name('.view');
        Route::put('update/{key}', 'update')->name('.update');
        Route::delete('delete/{key}', 'delete')->name('.delete');
    });
});
