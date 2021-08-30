<?php

namespace App\Tulpar\Timers;

use App\Tulpar\Logger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use React\EventLoop\TimerInterface;

class CleanStorageTimer
{
    private static function collect(string $directory): Collection
    {
        $paths = [];
        foreach (scandir($directory) as $path) {
            if (!($path == '.' || $path == '..' || str_starts_with($path, '.git') || $path == '.gitignore' || $path == 'index.php')) {
                $paths[] = realpath($directory . '/' . $path);
            }
        }

        return collect($paths);
    }

    public static function run(TimerInterface|null $timer)
    {
        Logger::info('Clearing storage files...');
        $files = collect([]);
        $files = $files->merge(static::collect(storage_path('app/music')));
        $files = $files->merge(static::collect(storage_path('tmp')));
        Logger::info('Deleting ' . $files->count() . ' file...');
        foreach ($files as $file) {
            File::delete($file);
        }
        Logger::info('Deleted ' . $files->count() . ' file.');
    }
}
