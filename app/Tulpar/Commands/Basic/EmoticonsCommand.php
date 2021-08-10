<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\Guild\Emoji;
use Discord\Parts\Guild\Guild;
use Discord\Repository\Guild\EmojiRepository;

class EmoticonsCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'emojis';

    public static string $description = 'Show all emojis in this server.';

    public static array $usages = [''];

    public static array $permissions = ['administrator'];

    public function run(): void
    {
        $this->discord->guilds->fetch($this->message->guild_id)->then(function (Guild $guild) {
            $guild->emojis->freshen()->then(function (EmojiRepository $repository) {
                if ($repository->count() > 0) {
                    $message = '';

                    /** @var Emoji $emoji */
                    foreach ($repository->toArray() as $emoji) {
                        $message .= '<' . $emoji->toReactionString() . '>' . ' ';
                    }

                    $this->message->channel->sendMessage('There is all emoticons of your server:')->then(function () use ($message) {
                        $this->message->channel->sendMessage($message);
                    });
                } else {
                    $this->message->channel->sendMessage('No any emojis exists on this server.');
                }
            });
        });
    }
}
