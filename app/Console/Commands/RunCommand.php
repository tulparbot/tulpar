<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\ServerStatisticsChannel;
use App\Models\TwitchConnection;
use App\Tulpar\Commands\Other\TwitchCommand;
use App\Tulpar\Tulpar;
use Discord\Builders\MessageBuilder;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Activity;
use Discord\Parts\User\Member;
use Discord\Repository\Guild\MemberRepository;
use Discord\Slash\Parts\Choices;
use Discord\Slash\Parts\Interaction;
use Discord\WebSockets\Intents;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RunCommand extends Command
{
    /**
     * @var bool $restartReceived
     */
    public static bool $restartReceived = true;

    /**
     * @var Tulpar|null $instance
     */
    public static Tulpar|null $instance = null;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start the bot';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        while (static::$restartReceived) {
            static::$restartReceived = false;

            try {
                $this->makeInstance();
                static::$instance->run($this->getOutput());
                static::$instance->stop();
            } catch (IntentException | Exception $exception) {
                $this->error($exception);
                static::$restartReceived = true;
            }
        }

        return CommandAlias::SUCCESS;
    }

    /**
     * @throws IntentException
     */
    public function makeInstance(): void
    {
        static::$instance = Tulpar::newInstance();
        static::$instance->options['token'] = (string)config('discord.token');
        static::$instance->options['loadAllMembers'] = true;
        static::$instance->options['intents'] = Intents::getAllIntents();
        static::$instance->getDiscord()->getLoop()->addPeriodicTimer(3, function () {
            foreach (Job::where('executed', false)->get() as $job) {
                $job->run();
            }
        });
        static::$instance->getDiscord()->getLoop()->addPeriodicTimer(5 * 60, function () {
            foreach (ServerStatisticsChannel::all() as $statisticsChannel) {
                /** @var Guild|null $guild */
                $guild = static::$instance->getDiscord()->guilds->get('id', $statisticsChannel->guild_id);
                if ($guild == null) {
                    continue;
                }

                $guild->channels->fetch($statisticsChannel->channel_id)->done(function (Channel $channel) use ($statisticsChannel) {
                    switch ($statisticsChannel->type) {
                        case 'total_users':
                            $channel->name = $channel->guild->member_count . ' Total Members';
                            $channel->guild->channels->save($channel);
                            break;

                        case 'bot_users':
                            $channel->guild->members->freshen()->done(function (MemberRepository $memberRepository) use ($channel) {
                                $count = 0;

                                /** @var Member $member */
                                foreach ($memberRepository as $member) {
                                    if ($member->user->bot) {
                                        $count++;
                                    }
                                }

                                $channel->name = $count . ' Bot\'s';
                                $channel->guild->channels->save($channel);
                            });
                            break;

                        case 'online_users':
                            $channel->guild->members->freshen()->done(function (MemberRepository $memberRepository) use ($channel) {
                                $count = 0;

                                /** @var Member $member */
                                foreach ($memberRepository as $member) {
                                    if ($member->status == 'online') {
                                        $count++;
                                    }
                                }

                                $channel->name = $count . ' Online Members';
                                $channel->guild->channels->save($channel);
                            });
                            break;
                    }
                });
            }
        });
        static::$instance->getDiscord()->getLoop()->addPeriodicTimer(5, function () {
            $activities = config('tulpar.activities');
            $activity = $activities[array_rand($activities)];
            $activity->name = Str::of($activity->name)
                ->replace('{prefix}', Tulpar::getPrefix())
                ->replace('{guild_count}', Tulpar::getInstance()->getDiscord()->guilds->count())
                ->replace('{member_count}', Tulpar::getInstance()->getDiscord()->users->count())
                ->replace('{command_count}', count(config('tulpar.commands')));

            /** @var Activity $_ */
            $_ = static::$instance->getDiscord()->factory(Activity::class, $activity);
            static::$instance->getDiscord()->updatePresence($_);
        });
        static::$instance->getDiscord()->getLoop()->addPeriodicTimer(15 * 60, function () {
            $connections = TwitchConnection::where('channel_id', '!=', null)->where('token', '!=', null)->where('accounts', '!=', null)->get();
            foreach ($connections as $connection) {
                $accounts = unserialize($connection->accounts) ?? [];
                if (count($accounts) > 0) {
                    $channel = static::$instance->getDiscord()->getChannel($connection->channel_id);
                    if ($channel != null) {
                        foreach ($accounts as $account) {
                            $status = TwitchCommand::getStatusFromUsername($account, $connection->token);
                            if ($status->live) {
                                $builder = MessageBuilder::new();
                                $builder = TwitchCommand::makeStatusMessage($builder, $status);
                                $channel->sendMessage($builder);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
