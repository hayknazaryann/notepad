<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'user_id', 'ip', 'user_agent', 'title', 'text', 'key', 'password'
    ];

    /**
     * @return string
     */
    public static function defaultTitle(): string
    {
        return 'Note ' . strtotime(date('Y-m-d H:i:s'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $limit
     * @param $offset
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function list($limit = 16, $offset = 0)
    {
        return $this->query()
            ->offset($offset)->limit($limit)
            ->orderByDesc('id')->get();
    }
}
