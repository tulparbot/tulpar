<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Models\ChannelRestrict;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class RestrictChannelCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'restrict-channel';

    public static string $description = 'Restrict current channel.';

    public static array $permissions = ['manage_channels'];

    public static array $usages = [
        '[image|text|link|command|disable]',
        '[image|text|link|command|disable] "restrict warning message"',
    ];

    public static array $requires = [0];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        $restrict = $this->userCommand->getArgument(0);
        $message = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : null;
        if (!in_array($restrict, ['image', 'text', 'link', 'command', 'disable'])) {
            $this->message->reply($this->translate('You can only restrict channel to: image, text, link or command.'));
            return;
        }

        $restricts = ChannelRestrict::where('server_id', $this->message->guild_id)->where('channel_id', $this->message->channel_id)->get();
        if ($restricts->count() > 0) {
            foreach ($restricts as $r) {
                $r->delete();
            }
        }

        if ($restrict == 'disable') {
            $this->message->reply($this->translate('Channel restriction is disabled'));
            return;
        }

        ChannelRestrict::create([
            'enable' => true,
            'server_id' => $this->message->guild_id,
            'channel_id' => $this->message->channel_id,
            'restrict' => $restrict,
            'message' => $message,
        ]);

        $this->message->reply($this->translate('This channel restricted to: :restrict', [
            'restrict' => $restrict,
        ]));
    }
}
