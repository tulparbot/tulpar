<?php


namespace App\Tulpar\Commands\Chat;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Logger;
use Discord\Parts\Guild\Emoji;
use Discord\Parts\Guild\Guild;
use Discord\Repository\Guild\EmojiRepository;

class EmoticonsCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'emoji';

    public static string $description = 'Show all emojis in this server.';

    public static array $usages = [''];

    public static array $permissions = ['administrator'];

    public static string $version = '1.1';

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        /** @var Guild $guild */
        $guild = $this->discord->guilds->get('id', $this->message->guild_id);

        $guild->emojis->freshen()->done(function (EmojiRepository $emojiRepository) use ($guild) {
            if ($emojiRepository->count() < 1) {
                $this->message->reply($this->translate('No any emojis exists on this server.'));
                return;
            }

            $message = '';

            /** @var Emoji $emoji */
            foreach ($emojiRepository as $emoji) {
                $icon = $emoji->animated ?
                    sprintf('<a:%s:%s>', $emoji->name, $emoji->id) :
                    sprintf('<%s>', $emoji->toReactionString());

                $message .= $icon . ' ';
            }

            $this->message->reply($message);
        }, function ($exception) {
            Logger::error($exception);
            $this->message->reply($this->translate('An error occurred on fetching emojis.'));
        });
    }
}
