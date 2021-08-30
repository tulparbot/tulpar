<?php


namespace App\Tulpar\Commands\Chat;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;

class HelloCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'hello';

    public static string $description = 'Say hello to user(s).';

    public static array $usages = [
        '',
        'user-id',
        '@username',
        '@user1 @user2 @user3....',
    ];

    public static array $permissions = [];

    public static string $version = '1.1';

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        if (!$this->userCommand->hasArgument(0)) {
            $this->message->reply($this->translate('Hi!'));
            return;
        }

        $users = [];
        foreach ($this->userCommand->arguments as $argument) {
            $users[] = Helpers::userTag($argument);
        }

        $this->message->reply($this->translate('Hi; :members!', [
            'members' => implode(', ', $users),
        ]));
    }
}
