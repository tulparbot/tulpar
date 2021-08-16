<?php


namespace App\Tulpar\Commands\Game;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ActivityCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'activity';

    public static string $description = 'Invite for a activity.';

    public static array $permissions = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Game;

    public static array $requires = [0];

    public static array $activities = [
        'poker' => '755827207812677713',
        'betrayal' => '773336526917861400',
        'youtube' => '755600276941176913',
        'fishington' => '814288819477020702',
        'chess' => '832012774040141894',
    ];

    public static array $usages = [
        '[poker|betrayal|youtube|fishington|chess]',
    ];

    public function run(): void
    {
        $activity = mb_strtolower($this->userCommand->getArgument(0));
        if (!array_key_exists($activity, static::$activities)) {
            $this->message->reply('You can only use activities: ``' . implode(', ', array_keys(static::$activities)) . '``');
            return;
        }
        $activity = static::$activities[$activity];

        $channel = Helpers::getMemberVoiceChannel($this->message->member->id);
        if ($channel == null) {
            $this->message->reply('Join a voice channel first.');
            return;
        }

        $client = new Client([
            'verify' => false,
        ]);

        $response = json_decode($client->post('https://discord.com/api/v8/channels/' . $channel->id . '/invites', [
            RequestOptions::BODY => json_encode([
                'max_age' => 86400,
                'max_uses' => 0,
                'target_application_id' => $activity,
                'target_type' => 2,
                'temporary' => false,
                'validate' => null,
            ]),
            RequestOptions::HEADERS => [
                'Authorization' => 'Bot ' . config('discord.token'),
                'Content-Type' => 'application/json',
            ],
        ])->getBody()->getContents());

        $this->message->reply('https://discord.com/invite/' . $response->code);
    }
}
