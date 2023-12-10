<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserInterface extends EloquentInterface
{
    /**
     * @param mixed $noteId
     * @return Collection
     */
    public function doesNotHaveNoteAccess(mixed $noteId): Collection;
}
