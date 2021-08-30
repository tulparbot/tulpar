<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\User\Member;

class MoveCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'move';

    public static string $description = 'Move you to a voice channel has a tagged user.';

    public static array $permissions = [];

    public static string $category = CommandCategory::General;

    public static array $requires = [0];

    public function run(): void
    {
        if (Helpers::getMemberVoiceChannel($this->message->member) == null) {
            $this->message->reply($this->translate('You are not in the voice channel.'));
            return;
        }

        $this->message->guild->members->fetch($this->userCommand->getArgument(0))->done(function (Member $member) {
            $channel = Helpers::getMemberVoiceChannel($member);
            if ($channel == null) {
                $this->message->reply($this->translate('The user is not joined to a voice channel.'));
                return;
            }

            $this->message->member->moveMember($channel)->done(function () {
                $this->message->reply($this->translate('okey'));
            }, function ($exception) {
                $this->message->reply($exception);
            });
        }, function ($exception) {
            $this->message->reply($this->translate('The user :member is not exists in this server.', [
                'member' => Helpers::userTag($this->userCommand->getArgument(0)),
            ]));
        });
    }
}
