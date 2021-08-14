<?php


namespace App\Tulpar\Commands\Management;


use App\Enums\CommandCategory;
use App\Models\ServerPrefix;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class PrefixCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'prefix';

    public static string $description = 'Set prefix of the server.';

    public static array $permissions = ['administrator'];

    public static array $usages = [
        'prefix',
        't!',
    ];

    public static array $requires = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        $args = explode(' ', $this->message->content);
        if (count($args) != 2) {
            $this->message->reply(static::getHelp());
            return;
        }

        $prefix = $args[1];

        $model = ServerPrefix::where('guild_id', $this->message->guild_id)->first();
        if ($model == null) {
            ServerPrefix::create([
                'guild_id' => $this->message->guild_id,
                'prefix' => $prefix,
            ]);
        }
        else {
            $model->update([
                'prefix' => $prefix,
            ]);
        }

        $this->message->reply('Server prefix is changed to "' . $prefix . '".');
    }
}
