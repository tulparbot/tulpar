<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Illuminate\Support\Str;

class InviteCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'invite';

    public static string $description = 'Get invite link';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = [''];

    public static string $version = '1.1';

    public function run(): void
    {
        $url = 'https://discord.com/api/oauth2/authorize?client_id={client_id}&permissions={permissions}&scope={scope}';
        $client_id = config('discord.client.id');
        $permissions = 8;
        $scopes = ['bot'];
        $url = Str::of($url)
            ->replace('{client_id}', urlencode($client_id))
            ->replace('{permissions}', urlencode($permissions))
            ->replace('{scope}', implode('%20', $scopes));

        $this->message->reply('🥰 There is my invite link: ' . $url);
    }
}
