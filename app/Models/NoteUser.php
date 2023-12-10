<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteUser extends Model
{
    use HasFactory;

    protected $table = 'note_users';

    protected $fillable = [
        'note_id', 'user_id', 'access'
    ];
}
