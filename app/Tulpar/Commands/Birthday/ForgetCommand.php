<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class ForgetCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'forget-birthday';

    public static string $description = 'Remove your birthday.';

    public static array $permissions = ['*'];

    public static array $usages = [''];

    public static array $requires = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $birthday = Birthday::where('server_id', $this->message->guild->id)->where('member_id', $this->message->member->id)->first();
        $birthday?->delete();
        $this->message->reply($this->translate('Your birth date is removed from this server.'));
    }
}
