<?php


namespace App\Tulpar\Commands\Authorization;


use App\Enums\CommandCategory;
use App\Models\User;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class RegisterCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'register';

    public static string $description = 'Register to the bot.';

    public static array $permissions = ['root'];

    public static string $category = CommandCategory::Authorization;

    public function run(): void
    {
        User::create([
            'uid' => $this->message->user_id,
            'name' => $this->message->member->user->username,
            'nickname' => $this->message->member->nick,
            'email' => $this->message->member->user->email,
            'avatar' => $this->message->member->user->avatar,
            'data' => serialize([
                'username' => $this->message->member->user->username,
                'avatar' => $this->message->member->user->avatar,
                'discriminator' => $this->message->member->user->discriminator,
                'public_flags' => $this->message->member->user->public_flags,
                'flags' => $this->message->member->user->flags,
                'banner' => $this->message->member->user?->banner,
                'banner_color' => $this->message->member->user?->banner_color,
                'accent_color' => $this->message->member->user?->accent_color,
                'locale' => $this->message->member->user->locale,
                'mfa_enabled' => $this->message->member->user->mfa_enabled,
                'email' => $this->message->member->user->email,
                'verified' => $this->message->member->user->verified,
            ]),
        ]);
    }
}
