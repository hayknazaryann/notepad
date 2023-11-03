<?php

namespace App\Repositories\Website;

use App\Models\Note;
use App\Models\User;
use App\Repositories\Website\Interfaces\NoteInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;

class NoteRepository implements NoteInterface
{
    /**
     * NoteRepository constructor.
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->model = $note;
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function create(Request $request): Model
    {
        return $this->getModel()->query()->create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'key' => Str::random(30)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function update(Request $request, string $key): ?Model
    {
        $model = $this->getModel()->query()->where(['key' => $key])->first();
        if (!$model) {
            return null;
        }

        $model->update([
            'title' => $request->input('title'),
            'text' => $request->input('text')
        ]);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function find(mixed $key): ?Model
    {
        return $this->getModel()->where(['key' => $key])->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return $this->model->list();
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request): mixed
    {
        $user = User::query()->find(Auth::id());
        $notes = $user->notes()->orderByDesc('id');
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            $notes = $notes->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('text', 'like', '%' . $keyword . '%');
        }

        return $notes->offset(0)->limit(10)->get();
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        if (!$this->model instanceof Model) {
            throw new InvalidArgumentException('Model must be an instance of Illuminate\Database\Eloquent\Model');
        }
        return $this->model;
    }

}
