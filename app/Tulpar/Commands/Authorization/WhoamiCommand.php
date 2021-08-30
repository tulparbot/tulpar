<?php


namespace App\Tulpar\Commands\Authorization;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use Discord\Parts\User\Member;

class WhoamiCommand extends BaseCommand implements CommandInterface
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
            $this->message->reply($this->translate('You are ``GLOBAL ROOT`` :member ☠️', [
                'member' => $member,
            ]));
            return;
        }

        if (Guard::isRoot($member)) {
            $this->message->reply($this->translate('You are ``ROOT`` :member 💣', [
                'member' => $member,
            ]));
            return;
        }

        if ($this->message->channel->guild->members->get('id', $member->id)->getPermissions($this->message->channel)->administrator === true) {
            $this->message->reply($this->translate('You are ``Server Administrator`` :member 🥳', [
                'member' => $member,
            ]));
            return;
        }

        Guard::clearModeratorCache();
        if (Guard::isModerator($this->message->guild, $member)) {
            $this->message->reply($this->translate('You are ``Moderator`` :member 🤓', [
                'member' => $member,
            ]));
            return;
        }

        $this->message->reply($this->translate('You are ``Member`` :member 💖', [
            'member' => $member,
        ]));
    }
}
