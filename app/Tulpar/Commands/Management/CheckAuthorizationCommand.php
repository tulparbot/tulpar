<?php


namespace App\Tulpar\Commands\Management;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use Discord\Parts\User\Member;

class CheckAuthorizationCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'whoami';

    public static string $description = 'Get your role in bot level.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = ['', '@user'];

    public static string $version = '1.2';

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->member;

        if ($this->userCommand->hasArgument(0)) {
            $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        }

        if (in_array($member->id, Guard::$globalRoots)) {
            $this->message->reply('You are ``GLOBAL ROOT`` ' . $member . ' ☠️');
            return;
        }

        if (Guard::isRoot($member)) {
            $this->message->reply('You are ``ROOT`` ' . $member . ' 💣');
            return;
        }

        if ($this->message->channel->guild->members->get('id', $member->id)->getPermissions($this->message->channel)->administrator === true) {
            $this->message->reply('You are ``Server Administrator`` ' . $member . ' 🥳');
            return;
        }

        $this->message->reply('You are ``Member`` ' . $member . ' 💖');
    }
}
