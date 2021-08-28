<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class RememberCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'remember-birthday';

    public static string $description = 'Add your birthday.';

    public static array $permissions = ['*'];

    public static array $usages = [
        '4 1 1881',
        'day month year',
    ];

    public static array $requires = [0, 1, 2];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $day = $this->userCommand->getArgument(0);
        $month = $this->userCommand->getArgument(1);
        $year = $this->userCommand->getArgument(2);

        if (!is_int($day) || !is_int($month) || !is_int($year)) {
            $this->message->reply('Please enter a valid date.');
            return;
        }

        if (!Birthday::make($this->message->guild, $this->message->member, $day, $month, $year)) {
            $this->message->reply('Please enter a valid date.');
            return;
        }

        $this->message->reply('Your birth date is saved to this server.');
    }
}
