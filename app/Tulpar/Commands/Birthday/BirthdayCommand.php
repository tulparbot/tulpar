<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\User\Member;

class BirthdayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'birthday';

    public static string $description = 'Show your birthday or another user\'s birthday.';

    public static array $permissions = ['*'];

    public static array $usages = ['', '@user'];

    public static array $requires = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $member = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(0) : null;

        if ($member == null) {
            $member = $this->message->member;
        }

        if (!$member instanceof Member) {
            $this->message->reply('Please enter a valid member');
            return;
        }

        $birthday = Birthday::where('server_id', $this->message->guild->id)->where('member_id', $member->id)->first();
        if ($birthday == null) {
            $this->message->reply('Birthday is not registered.');
            return;
        }

        $this->message->reply('The user (' . Helpers::userTag($member->id) . ') birthday is: ' . $birthday->day . '/' . $birthday->month . '/' . $birthday->year);
    }
}
