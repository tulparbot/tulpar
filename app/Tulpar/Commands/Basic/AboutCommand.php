<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Carbon;

class AboutCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'about';

    public static string $description = 'Show about the bot.';

    public static array $permissions = [];

    public function run(): void
    {
        $version = config('app.version');
        $memory_usage = memory_get_usage(true);
        $guilds = $this->discord->guilds->count();
        $users = 0;
        $runtime = Carbon::now()->addSeconds(intval(microtime(true) - TULPAR_START))->diffForHumans();

        /** @var Guild $guild */
        foreach ($this->discord->guilds as $guild) {
            $users += $guild->member_count;
        }

        $embed = new Embed($this->discord);
        $embed->setThumbnail($this->discord->avatar);
        $embed->setFooter('Bot ID: ' . $this->discord->id . ' - Runtime: ' . $runtime);

        $embed->addFieldValues('Version', $version);

        $embed->addFieldValues('Server Count', $guilds, true);
        $embed->addFieldValues('User Count', $users, true);
        $embed->addFieldValues('Latency', PingCommand::ping() . 'ms', true);

        $embed->addFieldValues('Memory Usage', bytesToHuman($memory_usage), true);
        $embed->addFieldValues('Creation Date', Carbon::make('8/12/2021')->toDateString(), true);
        $embed->addFieldValues('Author', Helpers::userTag('569169824056475679'), true);

        $this->message->channel->sendEmbed($embed);
    }
}
