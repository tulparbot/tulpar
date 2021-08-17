<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ServerController extends Controller
{
    public static function check(Server $server): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user_id = auth()->user()->uid;

        return Cache::remember('server-' . $server->id . '-auth-' . $user_id, Carbon::make('+6 hours'), function () use ($server, $user_id) {
            if ($server->owner_id == $user_id) {
                return true;
            }

            $roles = [];
            foreach ($server->roles as $role) {
                if ($role->permissions & 0x8) {
                    $roles[] = $role->id;
                }
            }

            foreach ($server->members as $member) {
                if ($member->user->id == $user_id) {
                    foreach ($member->roles as $role) {
                        if (in_array($role, $roles)) {
                            return true;
                        }
                    }

                    return false;
                }
            }

            return false;
        });
    }

    public function dashboard(Server $server)
    {
        abort_unless(static::check($server), 403);
        return response()->view('servers.dashboard', compact('server'));
    }
}
