<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;

class EmbedController extends Controller
{
    public function index(Server $server)
    {
        abort_unless($server->owner_id == auth()->user()->uid, 403);
        return response()->view('servers.embed', compact('server'));
    }
}
