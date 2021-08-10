<?php

namespace App\Http\Controllers;

use App\Models\AutoResponse;
use App\Models\Server;
use Illuminate\Http\Request;

class AutoResponderController extends Controller
{
    public function index(Request $request, Server $server)
    {
        $responses = AutoResponse::where('guild_id', $server->server_id)->get();
        return response()->view('servers.auto-responder', compact('server', 'responses'));
    }

    public function store(Request $request, Server $server)
    {
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
        $response->delete();
        return redirect()->back();
    }
}
