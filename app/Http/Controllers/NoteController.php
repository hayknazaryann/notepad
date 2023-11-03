<?php

namespace App\Http\Controllers;

use App\Enums\Extensions;
use App\Enums\StatusCodes;
use App\Http\Requests\Note\ImportRequest;
use App\Http\Requests\Note\StoreRequest;
use App\Http\Requests\Note\UpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repositories\Website\Interfaces\NoteInterface;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * @param NoteInterface $noteRepository
     */
    public function __construct(NoteInterface $noteRepository, FileService $fileService)
    {
        $this->noteRepository = $noteRepository;
        $this->fileService = $fileService;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $notes = $this->noteRepository->list();
        $showBtn = count($notes) == 10;
        return view('website.notes.index', [
            'notes' => $notes,
            'showBtn' => $showBtn
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loadItems(Request $request): JsonResponse
    {
        try {
            $notes = $this->noteRepository->search($request);
            $showBtn = count($notes) === 10;
            $view = view('website.notes.partials.items', [
                'data' => $notes,
                'showBtn' => $showBtn
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
     * @param string $key
     * @return JsonResponse
     */
    public function view(string $key): JsonResponse
    {
        $note = Note::query()->where(['key' => $key])->first();
        if (!$note->id) {
            return Response::json(['success' => false, 'error' => 'Not found'], StatusCodes::NOT_FOUND);
        }
        $note = new NoteResource($note);
        return Response::json([
            'success' => true,
            'data' => [
                'note' => $note,
                'url' => route('notepad.update', $note->key)
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
                    'title' => Note::defaultTitle(),
                    'text' => $note->text,
                    'extension' => $request->extension,
                    'note' => view('website.notes.partials.item', [
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
        if ($note = Note::query()->where(['key' => $key])->first()) {
            $note->delete();
            return Response::json([
                'success' => true,
                'msg' => 'Deleted successfully',
                'data' => [
                    'url' => route('notepad.store')
                ]
            ], StatusCodes::SUCCESS);
        }
        return Response::json(['success' => false, 'msg' => 'Not found'], StatusCodes::NOT_FOUND);
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
            return Response::json([
                'success' => false,
                'error' => __('Something went wrong!')
            ], StatusCodes::BAD_REQUEST);
        }
    }


}
