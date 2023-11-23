<?php

namespace App\Repositories\Website;

use App\Enums\PageSizes;
use App\Models\Note;
use App\Models\User;
use App\Repositories\Website\Interfaces\NoteInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;

class NoteRepository implements NoteInterface
{
    /** @var Model */
    protected Model $model;

    /** @var Client $elasticsearch */
    private $elasticsearch;

    /** @var GroupRepository $groupRepository */
    private $groupRepository;

    /**
     * NoteRepository constructor.
     * @param Note $note
     * @param Client $elasticsearch
     * @param GroupRepository $groupRepository
     */
    public function __construct(Note $note, Client $elasticsearch, GroupRepository $groupRepository)
    {
        $this->model = $note;
        $this->elasticsearch = $elasticsearch;
        $this->groupRepository = $groupRepository;
    }

    /** @inheritDoc */
    public function create(Request $request): Model
    {
        $last = $this->getModel()->query()->latest('ordering')->first();
        $groupName = $request->input('group');
        if (!empty($groupName)) {
            $group = $this->groupRepository->create(trim($groupName));
        }

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'key' => Str::random(30),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'ordering' => $last ? ($last->ordering + 1) : 1,
            'group_id' => $group->id ?? null,
        ];
        return $this->getModel()->query()->create($data);
    }

    /** @inheritDoc */
    public function update(Request $request, string $key): ?Model
    {
        $model = $this->getModel()->query()->where(['key' => $key])->first();
        if (!$model) {
            return null;
        }

        $groupName = $request->input('group');
        if (!empty($groupName)) {
            $group = $this->groupRepository->create(trim($groupName));
        }

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'key' => Str::random(30),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'group_id' => $group->id ?? $model->group_id,
        ];

        $model->update($data);

        return $model;
    }

    /** @inheritDoc */
    public function find(mixed $key): ?Model
    {
        return $this->getModel()->where(['key' => $key])->first();
    }

    /** @inheritDoc */
    public function pages($limit = PageSizes::LIMIT_10): int
    {
        $user = User::query()->find(Auth::id());
        $userNotesCount = $user->settings->notes;
        return $userNotesCount > $limit ? ceil($userNotesCount/$limit) : 1;
    }

    /** @inheritDoc */
    public function ordering(array $data): Model
    {
        $note = $this->getModel()->query()->where(['key' => $data['key']])->first();
        $noteOrdering = $note->ordering;
        $swapNote = $this->getModel()->query()->where(['key' => $data['swapKey']])->first();
        $swapNoteOrdering = $swapNote->ordering;
        $note->update(['ordering' => $swapNoteOrdering]);
        $swapNote->update(['ordering' => $noteOrdering]);
        return $note;
    }

    /** @inheritDoc */
    public function search(?array $data): Collection
    {
        if (! config('services.search.enabled')) {
            return $this->eloquentSearch($data);
        }

        $items = $this->searchOnElasticsearch($data);
        return $this->buildCollection($items);
    }

    /**
     * @param array|null $data
     * @return Collection
     */
    private function eloquentSearch(?array $data): Collection
    {
        $keyword = $data['keyword'] ?? null;
        $groupId = $data['group'] ?? null;
        $page = $data['page'] ?? 1;
        $limit = $data['pageSize'] ?? PageSizes::LIMIT_10;
        $offset = ($page - 1) * $limit;
        $wheres = ['user_id' => Auth::id()];
        if (!is_null($groupId)) {
            $wheres['group_id'] = $groupId;
        }
        $notes = $this->getModel()->query()->where($wheres);
        if (!is_null($keyword)) {
            $notes = $notes->where('title', 'like', '%' . $keyword . '%');
//                ->orWhere('text', 'like', '%' . $keyword . '%');
        }

        return $notes->orderByDesc('ordering')->offset($offset)->limit($limit)->get();
    }

    /**
     * @param array|null $data
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    private function searchOnElasticsearch(?array $data): array
    {
        $model = new ($this->getModel());
        $keyword = $data['keyword'] ?? null;
        $groupId = $data['group'] ?? null;
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 15;
        return $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'from' => $offset,
                'size' => $limit,
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'text', 'user_id'],
                        'query' => $keyword ?? false,
                    ],
                ],
            ],
        ])->asArray();
    }

    /**
     * @param array $items
     * @return Collection
     */
    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return $this->getModel()->query()->where(['user_id' => Auth::id()])->whereIn('id', $ids)->get();
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
