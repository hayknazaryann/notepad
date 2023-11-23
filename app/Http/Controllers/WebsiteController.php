<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class WebsiteController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('notes.index');
    }
}
