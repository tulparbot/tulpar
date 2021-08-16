<?php


namespace App\Tulpar\Commands\Development;


use App\Console\Commands\RunCommand;
use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Dialog;
use App\Tulpar\Guard;
use App\Tulpar\Tulpar;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Interactions\Interaction;

class RestartCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'restart';

    public static string $description = 'Stop and terminate bot processes and restart.';

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $version = '1.3';

    public static string $category = CommandCategory::Management;

    private function restart(bool $hard = false)
    {
        if ($hard) {
            RunCommand::$restartReceived = false;
            Tulpar::getInstance()->stop();
            sleep(1);

            $process = proc_open(
                PHP_BINARY . ' ' . base_path('tulpar') . ' run',
                [STDIN, STDOUT, STDERR],
                $pipes
            );

            if (is_resource($process)) {
                stream_set_blocking($pipes[0], true);
            }

            return;
        }

        RunCommand::$restartReceived = true;
        Tulpar::getInstance()->stop();
        sleep(1);
    }

    public function run(): void
    {
        $question = function (bool $hard) {
            $this->message->channel->sendMessage((Dialog::confirm('Are you sure to restart ' . config('app.name') . '?', listenerNo: function (Interaction $interaction) {
                if (!Guard::isRoot($interaction->member)) {
                    return;
                }

                $interaction->message->delete();
            }, listenerYes: function (Interaction $interaction) use ($hard) {
                if (!Guard::isRoot($interaction->member)) {
                    return;
                }
                
                $this->message->reply('Restarting...')->done(function () use ($hard) {
                    $this->restart($hard);
                });
            }))->setReplyTo($this->message));
        };

        $this->message->channel->sendMessage(
            MessageBuilder::new()
                ->setReplyTo($this->message)
                ->setContent('Select restart method:')
                ->addComponent(
                    SelectMenu::new()
                        ->addOption(Option::new('Normal', 'normal'))
                        ->addOption(Option::new('Hard', 'hard'))
                        ->setListener(function (Interaction $interaction, Collection $collection) use ($question) {
                            if (!Guard::isRoot($interaction->member)) {
                                return;
                            }

                            /** @var Option $option */
                            $option = $collection->first();
                            $question(!$option->getValue() == 'normal');
                        }, $this->discord)
                )
        );
    }
}
