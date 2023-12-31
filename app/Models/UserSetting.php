<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'user_settings';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'theme', 'notes', 'groups'
    ];
}
