<?php

namespace App\Providers;

use App\Repositories\Eloquent\NoteRepository;
use App\Repositories\Interfaces\NoteInterface;
use App\Repositories\Website\Interfaces\NoteInterface as WebsiteNoteInterface;
use App\Repositories\Website\NoteRepository as WebsiteNoteRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    public $bindings = [
        // Eloquent
        WebsiteNoteInterface::class => WebsiteNoteRepository::class,
        NoteInterface::class => NoteRepository::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
