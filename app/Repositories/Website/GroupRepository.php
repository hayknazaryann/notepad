<?php

namespace App\Repositories\Website;

use App\Models\NoteGroup;
use App\Repositories\Website\Interfaces\GroupInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class GroupRepository implements GroupInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * NoteRepository constructor.
     * @param NoteGroup $group
     */
    public function __construct(NoteGroup $group)
    {
        $this->model = $group;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getModel()->query()->where(['user_id' => Auth::id()])->get();
    }

    /**
     * @param string $title
     * @return Model|null
     */
    public function create(string $title): ?Model
    {
        $data = ['user_id' => Auth::id(), 'title' => $title];
        $query = NoteGroup::query();
        $exist = $query->where($data)->first();
        if ($exist) {
            return $exist;
        }
        return $query->create($data);
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
