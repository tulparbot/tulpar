<?php


namespace App\Tulpar\Commands\Development;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Logger;
use Discord\Parts\User\Activity;

class BotCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'bot';

    public static string $description = 'Set bot\'s activity status.';

    public static array $usages = [
        '--name="name"',
        '--url="url"',
        '--name="name" --url="url" --type="playing|streaming|listening|watching|custom|competing" --status="online|idle|dnd|invisible"',
    ];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::Management;

    public array $statuses = [Activity::STATUS_ONLINE, Activity::STATUS_DND, Activity::STATUS_IDLE, Activity::STATUS_INVISIBLE];

    public array $types = [
        Activity::TYPE_PLAYING => 'playing',
        Activity::TYPE_STREAMING => 'streaming',
        Activity::TYPE_LISTENING => 'listening',
        Activity::TYPE_WATCHING => 'watching',
        Activity::TYPE_CUSTOM => 'custom',
        Activity::TYPE_COMPETING => 'competing',
    ];

    public static array $values = [
        'name' => null,
        'url' => null,
        'type' => null,
        'status' => null,
    ];

    public function run(): void
    {
        $name = static::$values['name'] ?? config('app.name');
        $url = static::$values['url'] ?? 'https://github.com/isaeken';
        $type = static::$values['type'] ?? Activity::TYPE_PLAYING;
        $status = static::$values['status'] ?? Activity::STATUS_DND;

        if ($this->userCommand->hasOption('name')) {
            $name = $this->userCommand->getOption('name');
        }

        if ($this->userCommand->hasOption('url')) {
            $url = $this->userCommand->getOption('url');
        }

        if ($this->userCommand->hasOption('type')) {
            if (in_array($this->userCommand->getOption('type'), $this->types)) {
                $type = array_search($this->userCommand->getOption('type'), $this->types);
            }
        }

        if ($this->userCommand->hasOption('status')) {
            if (in_array($this->userCommand->getOption('status'), $this->statuses)) {
                $status = $this->userCommand->getOption('status');
            }
        }

        $__type = $this->types[$type];
        Logger::info(
            'Updating activity to: ' . PHP_EOL .
            "name => ``$name``" . PHP_EOL .
            "url => ``$url``" . PHP_EOL .
            "type => ``$__type``" . PHP_EOL .
            "status => ``$status``" . PHP_EOL
        );

        $activity = $this->discord->factory(Activity::class, [
            'name' => $name,
            'url' => $url,
            'type' => $type,
//            'status' => $status,
            'status' => Activity::STATUS_INVISIBLE,
        ]);

        static::$values['name'] = $name;
        static::$values['url'] = $url;
        static::$values['type'] = $type;
        static::$values['status'] = $status;

        $this->discord->updatePresence($activity, true, Activity::STATUS_INVISIBLE, true);

        $this->message->channel->sendMessage('Bot activity changed.');
    }
}
