<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('', [WebsiteController::class, 'index'])->name('home');

    Route::prefix('notes')->controller(NoteController::class)->name('notes')->group(function () {

        Route::get('', 'index')->name('.index');
        Route::get('create', 'create')->name('.create');
        Route::post('store', 'store')->name('.store');
        Route::get('view/{key}', 'view')->name('.view');
        Route::get('edit/{key}', 'edit')->name('.edit');
        Route::put('update/{key}', 'update')->name('.update');
        Route::delete('delete/{key}', 'delete')->name('.delete');
        Route::get('access/{key}', 'accessForm')->name('.accessForm');
        Route::post('access/{key}', 'access')->name('.access');
        Route::get('users/{key}', 'loadUsers')->name('.users');
        Route::post('unlock/{key}', 'unlock')->name('.unlock');
        Route::get('items', 'loadItems')->name('.items');
        Route::post('ordering', 'ordering')->name('.ordering');
        Route::post('import', 'import')->name('.import');
        Route::post('download/{key}/{extension}', 'download')->name('.download');
    });
});
