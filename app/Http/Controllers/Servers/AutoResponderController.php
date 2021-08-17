<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ServerController;
use App\Models\AutoResponse;
use App\Models\Server;
use Illuminate\Http\Request;

class AutoResponderController extends Controller
{
    public function index(Request $request, Server $server)
    {
        abort_unless(ServerController::check($server), 403);
        $responses = AutoResponse::where('guild_id', $server->server_id)->get();
        return response()->view('servers.auto-responder', compact('server', 'responses'));
    }

    public function store(Request $request, Server $server)
    {
        abort_unless(ServerController::check($server), 403);
        AutoResponse::create([
            'guild_id' => $server->server_id,
            'message' => $request->input('message'),
            'reply' => $request->input('reply'),
            'emoji' => $request->input('emoji'),
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, Server $server, AutoResponse $response)
    {
        abort_unless(ServerController::check($server), 403);
        $response->delete();
        return redirect()->back();
    }
}
