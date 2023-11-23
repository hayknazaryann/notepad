<?php

namespace App\Repositories\Eloquent;

use App\Models\Note;
use App\Repositories\Interfaces\NoteInterface;

class NoteRepository extends EloquentRepository implements NoteInterface
{
    /**
     * NoteRepository constructor.
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->model = $note;
    }



}
