<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class PageController extends Controller
{
    /**
     * @return Response
     */
    public function home(): Response
    {
        return response()->view('home');
    }

    public function servers()
    {
        /** @var Collection $servers */
        $servers = auth()->user()->servers;
        return \response()->view('servers', compact('servers'));
    }
}
