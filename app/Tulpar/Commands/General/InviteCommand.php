<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Illuminate\Support\Str;

class InviteCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'invite';

    public static string $description = 'Get invite link';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = [''];

    public static string $version = '1.2';

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $url = 'https://discord.com/api/oauth2/authorize?client_id={client_id}&permissions={permissions}&scope={scope}';
        $client_id = config('discord.client.id');
        $permissions = 8;
        $scopes = ['bot'];
        $url = Str::of($url)
            ->replace('{client_id}', urlencode($client_id))
            ->replace('{permissions}', urlencode($permissions))
            ->replace('{scope}', implode('%20', $scopes));

        $this->message->channel->sendMessage(MessageBuilder::new()
            ->setReplyTo($this->message)
            ->setContent($this->translate('🥰 There is my invite link'))
            ->addComponent(ActionRow::new()->addComponent(
                Button::new(Button::STYLE_LINK)
                    ->setLabel($this->translate('Invite Me'))
                    ->setUrl($url)
            )));
    }
}
