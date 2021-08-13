<?php


namespace App\Tulpar\Commands\Management;


use App\Enums\Align;
use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;

class StatisticsCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'stats';

    public static array $permissions = ['root'];

    public static array $usages = [
        '',
        '--tab=software|bot|server|client-permissions|*',
    ];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        $uptime = microtime(true) - TULPAR_START;
        $guilds = $this->discord->guilds->count();
        $members = 0;
        $tab = $this->userCommand->hasOption('tab') ? $this->userCommand->getOption('tab') : '*';

        /** @var Guild $guild */
        foreach ($this->discord->guilds as $guild) {
            $members += $guild->member_count;
        }

        $tabs = [];
        $tabs['software'] = [
            'title' => 'Software',
            'fields' => [
                ['Uptime', number_format($uptime, 2) . ' Seconds'],
                ['PHP', phpversion()],
                ['Version', config('app.version')],
            ],
        ];
        $tabs['bot'] = [
            'title' => 'Bot',
            'fields' => [
                ['Servers', $guilds],
                ['Members', $members],
                ['Nodes', 0],
            ],
        ];
        $tabs['server'] = [
            'title' => 'Server',
            'fields' => [
                ['Is Official Server', 'false'],
                ['Is Authorized Server', 'false'],
                ['Is Development Server', 'false'],
            ],
        ];
        $tabs['client-permissions'] = [
            'title' => 'Client Permissions',
            'fields' => [
                ['Is Root', 'false'],
                ['Is Administrator', 'false'],
                ['Is Member', 'false'],
            ],
        ];

        if ($tab != '*') {
            if (isset($tabs[$tab])) {
                $__tab = $tabs[$tab];
                $tabs = [];
                $tabs[$tab] = $__tab;
            }
        }

        $embed = new Embed($this->discord);
        $embed->setThumbnail($this->discord->avatar);
        $embed->setAuthor(config('app.name') . ' - ' . config('app.version'), $this->discord->avatar);
        $embed->title = '``' . Helpers::line('', Align::Center) . '``';

        foreach ($tabs as $tab) {
            $embed->addFieldValues(config('app.name'), '**' . $tab['title'] . '**');
            foreach ($tab['fields'] as $field) {
                $embed->addFieldValues($field[0], '``' . $field[1] . '``', true);
            }
        }

        $this->message->channel->sendEmbed($embed);
    }
}
