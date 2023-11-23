<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory, Searchable;

    /**
     * @var string
     */
    protected $table = 'notes';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'group_id', 'ordering',
        'ip', 'user_agent',
        'key', 'title', 'text', 'password'
    ];

    /**
     * @return void
     */
    public static function booted(): void
    {
        parent::booted();

        static::created(function ($model) {
            $user = $model->user;
            $user->settings()->increment('notes');
        });

        static::deleted(function($model) {
            $user = $model->user;
            $user->settings()->decrement('notes');
        });
    }

    /**
     * @return string
     */
    public static function defaultTitle(): string
    {
        return 'Note ' . strtotime(date('Y-m-d H:i:s'));
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(NoteGroup::class, 'group_id');
    }
}
