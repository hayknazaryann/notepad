<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends EloquentRepository implements UserInterface
{
    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }


    /** @inheritDoc */
    public function doesNotHaveNoteAccess(mixed $noteId): Collection
    {
        return $this->getModel()->query()->whereDoesntHave('accessNotes', function ($q) use ($noteId) {
            return $q->where(['note_id' => $noteId]);
        })->get();
    }



}
