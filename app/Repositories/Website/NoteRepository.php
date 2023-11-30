<?php

namespace App\Repositories\Website;

use App\Enums\PageSizes;
use App\Enums\StatusCodes;
use App\Models\Note;
use App\Repositories\Website\Interfaces\GroupInterface;
use App\Repositories\Website\Interfaces\NoteInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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

    /** @var GroupInterface $groupRepository */
    private $groupRepository;

    /**
     * NoteRepository constructor.
     * @param Note $note
     * @param Client $elasticsearch
     * @param GroupInterface $groupRepository
     */
    public function __construct(Note $note, Client $elasticsearch, GroupInterface $groupRepository)
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
            'password' => $request->input('password')
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

        $password = $request->input('password');
        $data = [
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'key' => Str::random(30),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'group_id' => $group->id ?? $model->group_id,
            'password' => $password
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
        $limit = $data['pageSize'] ?? PageSizes::LIMIT_10;
        $page = $data['page'] ?? 1;
        $offset = ($page - 1) * $limit;
        $wheres = ['user_id' => Auth::id()];
        if (!is_null($groupId)) {
            $wheres['group_id'] = $groupId;
        }
        $notes = $this->getModel()->query()->where($wheres);
        if (!is_null($keyword)) {
            $notes = $notes->where('title', 'like', '%' . $keyword . '%');
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

        /* Create Index if not exists */
        $params['index'] = $model->getSearchIndex();
        $this->elasticsearch->indices()->clearCache();
        $checkIndex = $this->elasticsearch->indices()->exists($params);
        if ($checkIndex->getStatusCode() === StatusCodes::NOT_FOUND) {
            $this->elasticsearch->indices()->create($params);
        }

        $limit = $data['pageSize'] ?? PageSizes::LIMIT_10;
        $page = $data['page'] ?? 1;
        $offset = ($page - 1) * $limit;

        $params = [
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'from' => $offset,
                'size' => $limit,
            ],
        ];

        if (!empty($data['keyword'])) {
            $params['body']['query'] = [
                'multi_match' => [
                    'fields' => ['title'],
                    'query' => $data['keyword'],
                ],
            ];
        }
        /* Get the results */
        return $this->elasticsearch->search($params)->asArray();
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

    /**
     * @param $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    private function paginateCollectionToPaginator($items, int $perPage = 10, ?int $page = null, array $options = []):LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
