<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ServerController;
use App\Models\Server;
use App\Tulpar\Image\LeaveImageGenerator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeavingSystemController extends Controller
{
    private function isValidImage(string $url): bool
    {
        $size = getimagesize($url);
        return strtolower(substr($size['mime'], 0, 5)) == 'image';
    }

    public function index(Request $request, Server $server): Response
    {
        abort_unless(ServerController::check($server), 403);
        return response()->view('servers.leaving-system', compact('server'));
    }

    public function preview(Request $request): mixed
    {
        $request->validate([
            'text' => 'nullable|sometimes|string|min:2|max:60',
            'foreground' => 'nullable|sometimes|regex:/^[a-f0-9]{6}$/i',
            'background' => 'nullable|sometimes|active_url',
        ]);

        $text = $request->input('text') ?? 'Bye';
        $foreground = '#' . ($request->input('foreground') ?? '000000');
        $background = $request->input('background') ?? null;

        if ($background != null && !$this->isValidImage($background)) {
            throw new Exception('Background is not a valid image.');
        }

        $generator = new LeaveImageGenerator(
            config('app.name'),
            $text,
            'https://cdn.discordapp.com/icons/871198795457781820/b434539e513b08395c82a4223633b9c1.jpg?size=1024',
            $background,
            $foreground,
        );

        return $generator->make()->response();
    }
}
