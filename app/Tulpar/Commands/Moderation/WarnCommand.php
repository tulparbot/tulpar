<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Enums\InfractionType;
use App\Models\Infraction;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\CommandTraits\HasEnumArgument;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use App\Tulpar\Helpers;
use Discord\Builders\MessageBuilder;
use Discord\Parts\User\Member;

class WarnCommand extends BaseCommand implements CommandInterface
{
    use HasEnumArgument;

    public static string $command = 'warn';

    public static string $description = 'Warn the user.';

    public static array $permissions = ['ban_members', 'kick_members', 'administrator', 'manage_guild'];

    public static array $usages = [
        '@user "reason"',
        '@user "reason" [custom|temp-ban|ban|hard-ban|kick|mute]',
    ];

    public static array $requires = [0, 1];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        $reason = $this->userCommand->getArgument(1);
        $type = $this->getEnumArgument(2, InfractionType::class, InfractionType::Custom, true);
        
        if ($type == null) {
            return;
        }

        if (!$member instanceof Member || !in_array($type, [InfractionType::Custom, InfractionType::TempBan, InfractionType::Ban, InfractionType::HardBan, InfractionType::Kick, InfractionType::Mute])) {
            $this->message->reply(static::getUsages());
            return;
        }

        if ($type == InfractionType::HardBan && !Guard::isRoot($this->message->member)) {
            $this->message->reply($this->translate('You are not authorized to use this command!'));
            return;
        }

        Infraction::make(
            $this->message->guild,
            $member,
            $type,
            $reason,
            $this->message->member,
        );

        $this->message->channel->sendMessage(
            MessageBuilder::new()
                ->setReplyTo($this->message)
                ->setContent($this->translate('User (:member) warned by reason: :reason', [
                    'member' => Helpers::userTag($this->message->user_id),
                    'reason' => $reason,
                ]))
        );
    }
}
