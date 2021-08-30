<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\User\Member;

class UnsetUserBirthdayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'unset-user-birthday';

    public static string $description = 'Remove another user\'s birthday.';

    public static array $permissions = ['administrator'];

    public static array $usages = ['@user'];

    public static array $requires = [1];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $member = $this->message->guild->members->get('id', $this->userCommand->getArgument(0));
        if (!$member instanceof Member) {
            $this->message->reply($this->translate('Please enter a valid member.'));
            return;
        }

        $birthday = Birthday::where('server_id', $this->message->guild->id)->where('member_id', $member->id)->first();
        $birthday?->delete();
        $this->message->reply($this->translate('Birth date is removed from this server.'));
    }
}
