<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\CommandTraits\HasMemberArgument;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Dialog;
use App\Tulpar\Helpers;
use App\Tulpar\Logger;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\User\Member;
use React\Promise\Promise;

class KickCommand extends BaseCommand implements CommandInterface
{
    use HasMemberArgument;

    public static string $command = 'kick';

    public static string $description = 'Kick the user.';

    public static array $permissions = ['kick_members'];

    public static array $usages = [
        'user-id',
        '@username',
        '@username [delay in seconds]',
    ];

    public static array $requires = [0];

    public static string $version = '1.4';

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        $member = $this->getMemberArgument(0, true);
        $delay = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : 0;

        if (!$member instanceof Member) {
            return;
        }

        if (!is_int($delay)) {
            $this->message->reply(static::getUsages());
            return;
        }

        $this->message->channel->sendMessage(Dialog::confirm(
            $this->translate('Are you serious to kick user: :member', [
                'member' => Helpers::userTag($member->id),
            ]),
            listenerNo: function (Interaction $interaction, MessageBuilder $builder) {
                $interaction->updateMessage(MessageBuilder::new()->setContent($this->translate('Kicking progress is canceled.')));
            },
            listenerYes: function (Interaction $interaction, MessageBuilder $builder) use ($member, $delay) {
                $interaction->updateMessage(MessageBuilder::new()->setContent($this->translate('User is kicking in :seconds seconds.', ['seconds' => $delay])));
                new Promise(function () use ($member, $delay) {
                    sleep($delay);

                    $except = function ($exception) {
                        Logger::error($exception);
                        $this->message->reply($this->translate('An error occurred when kicking the user.'));
                    };

                    $member->ban()->done(function () use ($member, $except) {
                        $this->message->channel->guild->unban($member)->done(function () use ($member) {
                            $this->message->reply($this->translate('The user ":member" is kicked.', [
                                'member' => Helpers::userTag($member->id),
                            ]));
                        }, $except);
                    }, $except);
                });
            }
        )->setReplyTo($this->message));
    }
}
