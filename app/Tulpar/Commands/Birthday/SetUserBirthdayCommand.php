<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\User\Member;

class SetUserBirthdayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'set-user-birthday';

    public static string $description = 'Add another user\'s birthday.';

    public static array $permissions = ['administrator'];

    public static array $usages = [
        '@member 4 1 1881',
        '@member day month year',
    ];

    public static array $requires = [0, 1, 2, 3];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $member = $this->message->guild->members->get('id', $this->userCommand->getArgument(0));
        $day = $this->userCommand->getArgument(1);
        $month = $this->userCommand->getArgument(2);
        $year = $this->userCommand->getArgument(3);

        if (!$member instanceof Member) {
            $this->message->reply($this->translate('Please enter a valid member.'));
            return;
        }

        if (!is_int($day) || !is_int($month) || !is_int($year)) {
            $this->message->reply($this->translate('Please enter a valid date.'));
            return;
        }

        if (!Birthday::make($this->message->guild, $member, $day, $month, $year)) {
            $this->message->reply($this->translate('Please enter a valid date.'));
            return;
        }

        $this->message->reply($this->translate('Your birth date is saved to this server.'));
    }
}
