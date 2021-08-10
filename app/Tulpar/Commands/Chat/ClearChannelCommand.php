<?php


namespace App\Tulpar\Commands\Chat;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Tulpar;
use Discord\Helpers\Collection;

class ClearChannelCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'clear';

    public static string $description = 'Clear messages in the channel.';

    public static array $permissions = ['manage_messages'];

    public static array $usages = ['', '--force=true|false'];

    public int $index = 0;

    public function run(): void
    {
        if ($this->userCommand->hasOption('force') && $this->userCommand->getOption('force') === true) {
            $this->message->channel->sendMessage(sprintf('"%s" İsimli Kanal Temizleniyor...', $this->message->channel->name));
            $channel = $this->message->channel;
            $newChannel = Tulpar::copyChannel($this->message->channel, $this->discord);

            $guild = Tulpar::findGuildFrom($this->message);
            $guild->channels->save($newChannel)->done(function () use ($guild, $channel, $newChannel) {
                $guild->channels->delete($channel)->done(function () use ($newChannel) {
                    $newChannel->sendMessage('Cleared');
                });
            });
        } else {
            $message = $this->message;

            $this->message->channel->sendMessage(sprintf(
                '"%s" İsimli Kanal Temizleniyor...',
                $this->message->channel->name,
            ))->then(function () use ($message) {
                $channel = $message->channel;
                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                        $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                            $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                        $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                            $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                                        $channel->sendMessage('Cleared');
                                                    });
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        }
    }
}
