<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Support\Str;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Logger;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Illuminate\Support\Carbon;

class BugCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'bug';

    public static string $description = 'Report a bug';

    public static array $usages = [
        'message....',
    ];

    public static array $permissions = ['*'];

    public static string $version = '1.0';

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $command = static::getCommand();
        if (mb_strlen($this->message->content) < mb_strlen($command) + 5) {
            $this->message->reply(static::getHelp());
            return;
        }

        $message = Str::of($this->message->content)->substr(mb_strlen($command) + 2);
        $channel = $this->discord->getChannel(config('tulpar.server.channel.moderation'));

        if ($channel === null) {
            Logger::critical('Moderation channel is not set.');
            $this->message->reply('Bug report cannot be sent. Please contact to an administrator.');
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
        $embed->setDescription($message);
        $embed->setFooter(Carbon::now());

        $builder = MessageBuilder::new()
            ->setContent('* New Bug Report From ' . ($this->message->member ?? $this->message->user) . ' *')
            ->addEmbed($embed);

        $channel->sendMessage($builder);

        $this->message->reply('Bug report sent. Thanks for support! 💖');
    }
}
