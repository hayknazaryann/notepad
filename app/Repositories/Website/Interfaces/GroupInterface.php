<?php

namespace App\Repositories\Website\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface GroupInterface
{
    /**
     * Get all instances of model
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Create record in to database
     * @param string $title
     * @return Model|null
     */
    public function create(string $title): ?Model;
}
