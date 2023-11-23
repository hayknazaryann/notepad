<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteGroup extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'note_groups';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'title'
    ];

    /**
     * @return void
     */
    public static function booted(): void
    {
        parent::booted();

        static::created(function ($model) {
            $user = $model->user;
            $user->settings()->increment('groups');
        });

        static::deleted(function($model) {
            $user = $model->user;
            $user->settings()->decrement('groups');
        });
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
