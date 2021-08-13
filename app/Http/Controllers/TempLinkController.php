<?php

namespace App\Http\Controllers;

use App\Support\Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TempLinkController extends Controller
{
    public function index(Request $request, string $uuid): Response|Application|ResponseFactory
    {
        abort_unless(Str::isUuid($uuid) && Cache::has('temp-link-' . $uuid), 404);
        return response(Cache::get('temp-link-' . $uuid), 200, ['Content-Type' => 'text/txt']);
    }
}
