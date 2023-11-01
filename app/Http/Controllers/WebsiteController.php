<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home');
    }

    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function loadMore(Request $request)
    {
        $modelName = $request->get('model');
        $path = "App\\Models\\$modelName";
        if(!class_exists($path)) {
            return response()->json([
                'success' => false,
                'error' => 'Class not found'
            ], 404);
        }

        $listMethod = $request->input('method');
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $id = $request->input('id');

        $model = app($path);
        $model = $id ? $model::where(['id' => $id])->first() : new $model();
        $data = $model->{$listMethod}($limit, $offset);
        $showBtn = count($data) == $limit;
        $view = 'website.pages.partials.' . $request->input('view');

        if (!view()->exists($view)) {
            return response()->json([
                'success' => false,
                'error' => 'View not found'
            ], 404);
        }

        return view($view, [
            'data' => $data,
            'model' => $model,
            'showBtn' => $showBtn
        ])->render();
    }
}
