<?php

namespace App\Providers;

use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Website\Interfaces\NoteInterface as WebsiteNoteInterface;
use App\Repositories\Website\NoteRepository as WebsiteNoteRepository;
use App\Repositories\Website\Interfaces\GroupInterface as WebsiteGroupInterface;
use App\Repositories\Website\GroupRepository as WebsiteGroupRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    public $bindings = [
        WebsiteNoteInterface::class  => WebsiteNoteRepository::class,
        WebsiteGroupInterface::class => WebsiteGroupRepository::class,
        UserInterface::class         => UserRepository::class
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
