<?php


namespace App\Tulpar\Commands\Authorization;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use App\Tulpar\Helpers;

class RootCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'root';

    public static string $description = 'Give or take root permission in the users.';

    public static array $usages = [
        'add @username',
        'remove @username',
        'add user-id',
        'remove user-id',
        'list',
    ];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static array $requires = [0];

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        $action = mb_strtolower($this->userCommand->getArgument(0));

        if ($action != 'add' && $action != 'remove' && $action != 'list') {
            $this->message->channel->sendMessage(static::getHelp());
            return;
        }

        if ($action != 'list') {
            if (!$this->userCommand->hasArgument(0)) {
                $this->message->channel->sendMessage(static::getHelp());
                return;
            }

            $id = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(1))->id;
        }

        switch ($action) {
            case 'add':
                Guard::addRoot($id);
                $this->message->reply('Added root permissions to user: ' . Helpers::userTag($id));
                break;

            case 'remove':
                Guard::removeRoot($id);
                $this->message->reply('Removed root permissions from user: ' . Helpers::userTag($id));
                break;

            case 'list':
                $text = '';
                foreach (Guard::getPermissions() as $permission) {
                    $text .= ' ' . Helpers::userTag($permission);
                }

                $text = "Roots:$text";

                $this->message->channel->sendMessage($text);
                break;
        }
    }
}
