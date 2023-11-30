<?php

namespace App\Http\Controllers;

use App\Enums\Extensions;
use App\Enums\PageSizes;
use App\Enums\StatusCodes;
use App\Http\Requests\Note\FilterRequest;
use App\Http\Requests\Note\ImportRequest;
use App\Http\Requests\Note\OrderRequest;
use App\Http\Requests\Note\PasswordRequest;
use App\Http\Requests\Note\StoreRequest;
use App\Http\Requests\Note\UpdateRequest;
use App\Repositories\Website\Interfaces\GroupInterface;
use App\Repositories\Website\Interfaces\NoteInterface;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class NoteController extends Controller
{

    /**
     * @var NoteInterface
     */
    private NoteInterface $noteRepository;

    /**
     * @var GroupInterface
     */
    private GroupInterface $groupRepository;

    /**
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * @param NoteInterface $noteRepository
     */
    public function __construct(
        NoteInterface $noteRepository,
        GroupInterface $groupRepository,
        FileService $fileService
    )
    {
        $this->noteRepository = $noteRepository;
        $this->groupRepository = $groupRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param FilterRequest $request
     * @return View
     */
    public function index(FilterRequest $request): View
    {
        $data = $request->query();
        $notes = $this->noteRepository->search($data);
        $groups = $this->groupRepository->all();
        $limit = $data['pageSize'] ?? PageSizes::LIMIT_10;
        $loadMore = count($notes) == $limit;
        $currentPage = $data['page'] ?? 1;
        return view('website.notes.index', [
            'notes'       => $notes,
            'groups'      => $groups,
            'loadMore'    => $loadMore,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $view =  view('website.notes.partials.form', [
            'url' => route('notes.store'),
            'groups' => $this->groupRepository->all(),
            'type' => 'create'
        ])->render();

        return Response::json([
            'success' => true,
            'data' => [
                'view' => $view
            ],
        ], StatusCodes::SUCCESS);
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $note = $this->noteRepository->create($request);
            return Response::json([
                'success' => true,
                'msg' => 'Your note has been created.',
                'data' => [
                    'title' => $note->title,
                    'text' => $note->text,
                    'extension' => $request->extension,
                    'note' => view('website.notes.partials.grid-item', [
                        'note' => $note
                    ])->render(),
                ]
            ], StatusCodes::CREATED);
        } catch (\Exception $exception) {
            Log::error('Note create: ' . $exception->getMessage());
            return Response::json([
                'success' => false,
                'error' => 'Something went wrong!'
            ], StatusCodes::BAD_REQUEST);
        }
    }

    /**
     * @param string $key
     * @return JsonResponse
     */
    public function view(string $key): JsonResponse
    {
        $note = $this->noteRepository->find($key);
        if (!$note) {
            return Response::json(['success' => false, 'error' => 'Not found'], StatusCodes::NOT_FOUND);
        }

        $viewName = $note->password ? 'password-form' : 'details';

        return Response::json([
            'success' => true,
            'data' => [
                'view' => view("website.notes.partials.{$viewName}", ['note' => $note, 'action' => 'view'])->render()
            ],
        ], StatusCodes::SUCCESS);
    }

    /**
     * @param string $key
     * @return JsonResponse
     */
    public function edit(string $key): JsonResponse
    {
        $note = $this->noteRepository->find($key);
        $viewName = $note->password ? 'password-form' : 'form';
        $view =  view("website.notes.partials.{$viewName}", [
            'groups' => $this->groupRepository->all(),
            'note' => $note,
            'url' => route('notes.update', $key),
            'type' => 'edit',
            'action' => 'edit'
        ])->render();

        return Response::json([
            'success' => true,
            'data' => [
                'view' => $view
            ],
        ], StatusCodes::SUCCESS);
    }

    /**
     * @param UpdateRequest $request
     * @param string $key
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, string $key): JsonResponse
    {
        try {
            $note = $this->noteRepository->update($request, $key);
            return Response::json([
                'success' => true,
                'msg' => 'Your note has been updated.',
                'data' => [
                    'title' => $note->title,
                    'text' => $note->text,
                    'extension' => $request->extension
                ]
            ], StatusCodes::CREATED);
        } catch (\Exception $exception) {
            Log::error('Note update ' . $key . ': ' . $exception->getMessage());
            return Response::json([
                'success' => false,
                'msg' => 'Something went wrong!'
            ], StatusCodes::BAD_REQUEST);
        }
    }

    /**
     * @param $key
     * @return JsonResponse
     */
    public function delete($key): JsonResponse
    {
        if ($note = $this->noteRepository->find($key)) {
            $note->delete();
            return Response::json([
                'success' => true,
                'msg' => 'Deleted successfully',
            ], StatusCodes::SUCCESS);
        }
        return Response::json(['success' => false, 'msg' => 'Not found'], StatusCodes::NOT_FOUND);
    }

    /**
     * @param FilterRequest $request
     * @return JsonResponse
     */
    public function loadItems(FilterRequest $request): JsonResponse
    {
        try {
            $data = $request->query();
            $notes = $this->noteRepository->search($data);
            $limit = $data['pageSize'] ?? PageSizes::LIMIT_10;
            $loadMore = count($notes) == $limit;
            $currentPage = $data['page'] ?? 1;
            $view = view("website.notes.partials.grid-items", [
                'data'        => $notes,
                'loadMore'    => $loadMore,
                'currentPage' => $currentPage
            ])->render();
            return response()->json([
                'success' => true,
                'view' => $view
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong'
            ], 400);
        }
    }

    /**
     * @param ImportRequest $request
     * @return JsonResponse
     */
    public function import(ImportRequest $request): JsonResponse
    {
        try {
            $content = $this->fileService->content($request->file('file'));
            return Response::json([
                'success' => true,
                'data' => [
                    'content' => htmlspecialchars($content)
                ]], StatusCodes::SUCCESS);
        } catch (\Exception $exception) {
            Log::error('Note import error: ' . $exception->getMessage());
            return Response::json([
                'success' => false,
                'error' => __('Something went wrong!')
            ], StatusCodes::BAD_REQUEST);
        }
    }

    /**
     * @param string $key
     * @param string $extension
     * @return JsonResponse
     */
    public function download(string $key, string $extension): JsonResponse
    {
        try {
            if (!in_array($extension, Extensions::all())) {
                return Response::json([
                    'success' => false,
                    'error' => __('Wrong extension !')
                ], StatusCodes::BAD_REQUEST);
            }

            $note = $this->noteRepository->find($key);
            return Response::json([
                'success' => true,
                'data' => [
                    'title' => $note->title,
                    'text' => $note->text,
                    'extension' => $extension
                ]
            ], StatusCodes::SUCCESS);
        } catch (\Exception $exception) {
            Log::error('Note download error: ' . $exception->getMessage());
            return Response::json([
                'success' => false,
                'error' => __('Something went wrong!')
            ], StatusCodes::BAD_REQUEST);
        }
    }

    /**
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function ordering(OrderRequest $request): JsonResponse
    {
        try {
            $this->noteRepository->ordering($request->validated());
            return Response::json([
                'success' => true,
            ], StatusCodes::SUCCESS);
        } catch (\Exception $exception) {
            Log::error('Note ordering error: ' . $exception->getMessage());
            return Response::json([
                'success' => false,
                'error' => __('Something went wrong!')
            ], StatusCodes::BAD_REQUEST);
        }

    }

    /**
     * @param PasswordRequest $request
     * @param string $key
     * @return JsonResponse
     */
    public function unlock(PasswordRequest $request, string $key): JsonResponse
    {
        $password = $request->input('password');
        $note = $this->noteRepository->find($key);

        if ($password !== $note->password) {
            return Response::json([
                'success' => true,
                'msg' => 'Wrong password !',
            ], StatusCodes::BAD_REQUEST);
        }

        $action = $request->input('action');
        $view = '';

        switch ($action) {
            case 'view':
                $view = view("website.notes.partials.details", [
                    'note' => $note
                ])->render();
                break;
            case 'edit':
                $view = view("website.notes.partials.form", [
                    'groups' => $this->groupRepository->all(),
                    'note' => $note,
                    'url' => route('notes.update', $key),
                    'type' => 'edit'
                ])->render();
                break;
            case 'delete':
                break;
        }


        return Response::json([
            'success' => true,
            'data' => [
                'view' => $view
            ],
        ], StatusCodes::SUCCESS);
    }

}
