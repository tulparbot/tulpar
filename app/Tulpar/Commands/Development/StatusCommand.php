<?php


namespace App\Tulpar\Commands\Development;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use App\Tulpar\Tulpar;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use Illuminate\Support\Carbon;

class StatusCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'status';

    public static string $description = 'Show bot status.';

    public static array $usages = [''];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $version = '1.1';

    public static string $category = CommandCategory::Development;

    public function run(): void
    {
        $prefix = Tulpar::getPrefix();
        $guild_prefix = Tulpar::getPrefix($this->message->guild);
        $command_unknown_alert = config('tulpar.command.unknown_alert') ? 'active' : 'passive';

        $log_channel = '<#' . config('tulpar.server.channel.log') . '>';
        $moderation_channel = '<#' . config('tulpar.server.channel.moderation') . '>';
        $debug_channel = '<#' . config('tulpar.server.channel.debug') . '>';

        $emergency_logs = config('tulpar.server.logging.emergency') ? 'active' : 'passive';
        $alert_logs = config('tulpar.server.logging.alert') ? 'active' : 'passive';
        $critical_logs = config('tulpar.server.logging.critical') ? 'active' : 'passive';
        $error_logs = config('tulpar.server.logging.error') ? 'active' : 'passive';
        $warning_logs = config('tulpar.server.logging.warning') ? 'active' : 'passive';
        $notice_logs = config('tulpar.server.logging.notice') ? 'active' : 'passive';
        $info_logs = config('tulpar.server.logging.info') ? 'active' : 'passive';
        $debug_logs = config('tulpar.server.logging.debug') ? 'active' : 'passive';

        $commands = config('tulpar.commands');
        $aliases = config('tulpar.aliases');
        $filters = config('tulpar.filters');
        $timers = config('tulpar.timers');

        $prefixEmbed = new Embed($this->discord);
        $prefixEmbed->setTitle('Prefix');
        $prefixEmbed->setDescription('```Global Prefix: ' . $prefix . PHP_EOL . 'Server Prefix: ' . $guild_prefix . '```');

        $logsEmbed = new Embed($this->discord);
        $logsEmbed->setTitle('Logs');
        $logsEmbed->setDescription(
            '```' .
            'Log Channel: ' . $log_channel . PHP_EOL .
            'Moderation Channel: ' . $moderation_channel . PHP_EOL .
            'Debug Channel: ' . $debug_channel . PHP_EOL .
            'Emergency Logs: ' . $emergency_logs . PHP_EOL .
            'Alert Logs: ' . $alert_logs . PHP_EOL .
            'Critical Logs: ' . $critical_logs . PHP_EOL .
            'Error Logs: ' . $error_logs . PHP_EOL .
            'Warning Logs: ' . $warning_logs . PHP_EOL .
            'Notice Logs: ' . $notice_logs . PHP_EOL .
            'Info Logs: ' . $info_logs . PHP_EOL .
            'Debug Logs: ' . $debug_logs .
            '```'
        );

        $commandEmbed = new Embed($this->discord);
        $commandEmbed->setTitle('Commands');
        $commandEmbed->setDescription(
            '```' .
            'Command Unknown Alerts: ' . $command_unknown_alert . PHP_EOL .
            'Command Count: ' . count($commands) . PHP_EOL .
            'Commands: ' . PHP_EOL . implode(PHP_EOL, $commands) . PHP_EOL .
            '```'
        );

        $_ = '';
        foreach ($aliases as $command => $array) {
            $_ .= $command . ' => ' . PHP_EOL;
            foreach ($array as $alias) {
                $_ .= '  ` ' . $alias . PHP_EOL;
            }
        }

        $aliasEmbed = new Embed($this->discord);
        $aliasEmbed->setTitle('Aliases');
        $aliasEmbed->setDescription(
            '```' .
            'Alias Count: ' . count($aliases) . PHP_EOL .
            'Aliases: ' . PHP_EOL . $_ . PHP_EOL .
            '```'
        );

        $filterEmbed = new Embed($this->discord);
        $filterEmbed->setTitle('Filters');
        $filterEmbed->setDescription(
            '````' .
            'Filter Count: ' . count($filters) . PHP_EOL .
            'Filters: ' . PHP_EOL . implode(PHP_EOL, $filters) . PHP_EOL .
            '```'
        );

        $_ = '';
        foreach ($timers as $interval => $array) {
            $_ .= $interval . ' Seconds => ' . PHP_EOL;
            foreach ($array as $timer) {
                $_ .= '  ` ' . $timer . PHP_EOL;
            }
        }

        $timerEmbed = new Embed($this->discord);
        $timerEmbed->setTitle('Timers');
        $timerEmbed->setDescription(
            '```' .
            'Timer Count: ' . count($timers) . PHP_EOL .
            'Timers: ' . PHP_EOL . $_ . PHP_EOL .
            '```'
        );

        $botEmbed = new Embed($this->discord);
        $botEmbed->setTitle(config('app.name'));
        $botEmbed->setDescription(
            '```' .
            'Version: ' . config('app.version') . PHP_EOL .
            'Uptime: ' . Carbon::now()->addSeconds(intval(microtime(true) - TULPAR_START))->diffForHumans() . PHP_EOL .
            'Memory Usage: ' . bytesToHuman(memory_get_usage(true)) .
            '```'
        );

        $this->message->channel->sendMessage(MessageBuilder::new()
            ->setReplyTo($this->message)
            ->setContent('Select an option for show status:')
            ->addComponent(
                SelectMenu::new()
                    ->addOption(Option::new('Prefix', 'prefix'))
                    ->addOption(Option::new('Logs', 'logs'))
                    ->addOption(Option::new('Commands', 'commands'))
                    ->addOption(Option::new('Aliases', 'aliases'))
                    ->addOption(Option::new('Filters', 'filters'))
                    ->addOption(Option::new('Timers', 'timers'))
                    ->addOption(Option::new('Bot', 'bot'))
                    ->setListener(function (Interaction $interaction, Collection $collection) use ($prefixEmbed, $logsEmbed, $commandEmbed, $aliasEmbed, $filterEmbed, $timerEmbed, $botEmbed) {
                        if (!Guard::isRoot($interaction->member)) {
                            return;
                        }

                        $interaction->message->delete()->done(function () use ($interaction, $collection, $prefixEmbed, $logsEmbed, $commandEmbed, $aliasEmbed, $filterEmbed, $timerEmbed, $botEmbed) {
                            $builder = MessageBuilder::new();
                            $builder->setReplyTo($this->message);
                            $value = $collection->first()->getValue();

                            if ($value == 'prefix') {
                                $builder->addEmbed($prefixEmbed);
                            }
                            else if ($value == 'logs') {
                                $builder->addEmbed($logsEmbed);
                            }
                            else if ($value == 'commands') {
                                $builder->addEmbed($commandEmbed);
                            }
                            else if ($value == 'aliases') {
                                $builder->addEmbed($aliasEmbed);
                            }
                            else if ($value == 'filters') {
                                $builder->addEmbed($filterEmbed);
                            }
                            else if ($value == 'timers') {
                                $builder->addEmbed($timerEmbed);
                            }
                            else if ($value == 'bot') {
                                $builder->addEmbed($botEmbed);
                            }

                            $this->message->channel->sendMessage($builder);
                        });
                    }, $this->discord)
            ));
    }
}
