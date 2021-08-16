<?php

namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use App\Models\TwitchConnection;
use Illuminate\Http\Request;

class TwitchController extends Controller
{
    public function callback(Request $request)
    {
        if (!$request->has('tulpar')) {
            return response('<html><body><script>window.location.href = "?tulpar=" + window.location.href.replace("#", "&");</script></body></html>', 200);
        }

        abort_unless($request->has('access_token'), 403);

        TwitchConnection::create([
            'user_id' => auth()->user()->uid,
            'token' => $request->get('access_token'),
        ]);

        return response('ok');
    }
}
