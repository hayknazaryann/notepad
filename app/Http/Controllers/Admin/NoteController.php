<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::query()->paginate(10);
        return view('admin.notes.index', [
            'notes' => $notes
        ]);
    }
}
