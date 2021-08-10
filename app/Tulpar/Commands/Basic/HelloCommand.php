<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Translator;

class HelloCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'hello';

    public static string $description = 'Say hello to given user id.';

    public static array $usages = [
        '',
        'user-id',
        '@username',
    ];

    public static array $permissions = [];

    public function run(): void
    {
        $user = Helpers::userTag($this->userCommand->hasArgument(0) ?
            $this->userCommand->getArgument(0) :
            $this->message->user->id
        );

        $this->message->channel->sendMessage(__translate(
            'Hi, :username!',
            Translator::findLocale($this->message, $this->discord),
            [':username' => $user],
        ));
    }
}
