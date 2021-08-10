<?php

namespace App\Http\Controllers;

use App\Models\Server;

class ServerController extends Controller
{
    public function dashboard(Server $server)
    {
        abort_unless($server->owner_id == auth()->user()->uid, 403);
        return response()->view('servers.dashboard', compact('server'));
    }
}
