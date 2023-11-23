<?php

namespace App\Repositories\Website\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface NoteInterface
{
    /**
     * Create record in to database
     * @param Request $request
     * @return Model
     */
    public function create(Request $request): Model;

    /**
     * Update record in the database
     *
     * @param Request $request
     * @param string $key
     * @return Model|null
     */
    public function update(Request $request, string $key): ?Model;

    /**
     * Find a record by Primary Key
     *
     * @param mixed $key
     * @return Model|null
     */
    public function find(mixed $key): ?Model;

    /**
     * @param int $limit
     * @return int
     */
    public function pages(int $limit): int;

    /**
     * Order notes
     *
     * @param array $data
     * @return Model
     */
    public function ordering(array $data): Model;

    /**
     * @param array|null $data
     */
    public function search(?array $data): Collection;

}
