<?php

namespace App\Tulpar\Commands\Other;

use App\Enums\CommandCategory;
use App\Models\TwitchConnection;
use App\Support\Str;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Tulpar;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TwitchCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'twitch';

    public static string $description = 'Check the user is live in the Twitch.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public static array $requires = [0];

    public function run(): void
    {
        if (TwitchConnection::findToken($this->message->user_id) == null) {
            $builder = MessageBuilder::new()
                ->setReplyTo($this->message)
                ->setContent('Please connect your account to Twitch.')
                ->addComponent(ActionRow::new()->addComponent(
                    Button::new(Button::STYLE_LINK)
                        ->setLabel('Connection')
                        ->setUrl('https://id.twitch.tv/oauth2/authorize?response_type=token&client_id=' . config('twitch.client.id') . '&redirect_uri=' . config('app.url') . '/auth/callback/twitch&scope=user_read')
                ));
            $this->message->channel->sendMessage($builder);
            return;
        }

        $status = static::getStatusFromUsername($this->userCommand->getArgument(0), TwitchConnection::findToken($this->message->user_id));
        $this->message->channel->sendMessage(static::makeStatusMessage(MessageBuilder::new()->setReplyTo($this->message), $status));
    }

    public static function getStatusFromUsername(string $username, string $token): object
    {
        $client_id = config('twitch.client.id');
        return Cache::remember('twitch-status-' . $username, Carbon::make('+10 minutes'), function () use ($username, $client_id, $token) {
            $client = new Client;
            $response = json_decode($client->get('https://api.twitch.tv/helix/streams?user_login=' . $username, [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/vnd.twitchtv.v5+json',
                    'Client-Id' => $client_id,
                    'Authorization' => 'Bearer ' . $token,
                ],
            ])->getBody()->getContents());

            $out = (object)[
                'live' => false,
                'username' => $username,
                'user_id' => null,
                'user_login' => null,
                'game_id' => null,
                'game_name' => null,
                'title' => null,
                'viewer_count' => null,
                'started_at' => null,
                'language' => null,
                'thumbnail_url' => null,
            ];

            if (count($response->data)) {
                foreach ($response->data as $user) {
                    if (mb_strtolower($user->user_login) == $username && $user->type == 'live') {
                        $out->live = true;
                        $out->username = $username;
                        $out->user_id = $user->user_id;
                        $out->user_login = $user->user_login;
                        $out->game_id = $user->game_id;
                        $out->game_name = $user->game_name;
                        $out->title = $user->title;
                        $out->viewer_count = $user->viewer_count;
                        $out->started_at = Carbon::make($user->started_at);
                        $out->language = $user->language;
                        $out->thumbnail_url = Str::of($user->thumbnail_url)->replace('{width}', '440')->replace('{height}', '248')->__toString();
                        break;
                    }
                }
            }

            return $out;
        });
    }

    public static function makeStatusMessage(MessageBuilder $builder, object $status): MessageBuilder
    {
        $embed = new Embed(Tulpar::getInstance()->getDiscord());

        if (!$status->live) {
            $embed->setTitle($status->username);
            $embed->setDescription('Is currently offline.');
            $builder->addEmbed($embed);
            return $builder;
        }

        $embed->setTitle($status->title);
        $embed->setDescription('twitch.tv/' . $status->username);
        $embed->setThumbnail($status->thumbnail_url);
        $builder->addEmbed($embed);
        $builder->addComponent(ActionRow::new()->addComponent(
            Button::new(Button::STYLE_LINK)
                ->setLabel('Join Stream')
                ->setUrl('https://twitch.tv/' . $status->username)
        ));

        return $builder;
    }
}
