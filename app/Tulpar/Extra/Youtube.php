<?php

namespace App\Tulpar\Extra;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Youtube
{
    /**
     * @param string      $query      search query
     * @param int         $max_result min 1, max 50
     * @param string      $order      ['date', 'rating', 'relevance', 'title', 'viewCount']
     * @param string|null $pageToken
     * @return Collection
     */
    public static function search(string $query, int $max_result = 5, string $order = 'viewCount', string|null $pageToken = null): Collection
    {
        $query = implode('+', explode(' ', $query));
        $url = "https://www.googleapis.com/youtube/v3/search?q=$query&order=" . $order . '&part=snippet&type=video&maxResults=' . $max_result . '&key=' . env('YOUTUBE_API_KEY');

        if ($pageToken !== null) {
            $url .= '&pageToken=' . $pageToken;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response);

        if (isset($result->items)) {
            return collect($result->items);
        }

        return collect([]);
    }

    /**
     * @param string $id
     * @return string
     */
    public static function download(string $id): string
    {
        $bin = base_path('bin/youtube-dl.exe');
        $url = 'https://www.youtube.com/watch?v=' . $id;
        $filename = storage_path('/app/music/' . Str::random() . '.mp3');
        $command = implode(' ', [$bin, '-x', '--audio-format', 'mp3', '--output', $filename, $url]);
        shell_exec($command);
        return $filename;
    }
}
