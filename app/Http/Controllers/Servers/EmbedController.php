<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ServerController;
use App\Models\Server;

class EmbedController extends Controller
{
    public function index(Server $server)
    {
        abort_unless(ServerController::check($server), 403);
        return response()->view('servers.embed', compact('server'));
    }
}
