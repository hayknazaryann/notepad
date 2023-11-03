<?php

namespace App\Repositories\Eloquent;

use App\Models\Application;
use App\Models\Note;
use App\Models\User;
use App\Repositories\Interfaces\ApplicationInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteRepository extends EloquentRepository implements ApplicationInterface
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
