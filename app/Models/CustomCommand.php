<?php

namespace App\Models;

use App\Tulpar\Workflow\Workflow;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCommand extends Model
{
    use HasFactory;

    protected $table = 'custom_commands';

    protected $fillable = [
        'guild_id',
        'command',
        'workflow',
    ];

    public function execute(Message $message, Discord $discord): void
    {
        $workflow = new Workflow(
            unserialize($this->getAttribute('workflow')) ?? [],
            $message,
            $discord,
        );
        $workflow->run();
    }

    /**
     * @param Guild|string $guild
     * @param string       $command
     * @return CustomCommand|null
     */
    public static function find(Guild|string $guild, string $command): CustomCommand|null
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        return CustomCommand::where('guild_id', $guild)->where('command', $command)->first();
    }
}
