<?php


namespace App\Tulpar\Commands\Management;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class RootCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'root';

    public static string $description = 'Give or take root permission in the users.';

    public static array $usages = [
        'add @username',
        'remove @username',
        'add user-id',
        'remove user-id',
    ];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static array $requires = [0, 1];

    public function run(): void
    {
        $action = $this->userCommand->getArgument(0);
        $id = $this->userCommand->getArgument(1);

        if ($action != 'add' && $action != 'remove') {
            $this->message->channel->sendMessage(static::getHelp());
            return;
        }

        if ($action == 'add') {
            file_put_contents(base_path('administrators.txt'), PHP_EOL . $id, FILE_APPEND);
            $this->message->channel->sendMessage('Added root user.');
        } else {
            $administrators = '';
            $contents = file_get_contents(base_path('administrators.txt'));
            foreach (preg_split("/((\r?\n)|(\r\n?))/", $contents) as $administrator) {
                if ($administrator != $id) {
                    $administrators .= $administrator . PHP_EOL;
                }
            }
            file_put_contents(base_path('administrators.txt'), $administrators);
            $this->message->channel->sendMessage('Removed root user.');
        }
    }
}
