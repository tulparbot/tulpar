<?php


namespace App\Tulpar\Commands\Chat;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\User\Member;

class ClearChannelCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'clear';

    public static string $description = 'Clear messages in the channel.';

    public static array $permissions = ['manage_messages'];

    public static array $usages = ['', '--force=true|false'];

    public static string $version = '1.1';

    public int $index = 0;

    public function run(): void
    {
        if ($this->userCommand->hasOption('force') && $this->userCommand->getOption('force') === true) {
            $this->message->reply($this->message->channel->name . ' is clearing...')->done(function () {
                sleep(1);
                $guild = $this->message->channel->guild;
                $channel = $this->message->channel;
                $newChannel = Helpers::copyChannel($this->message->channel, $this->discord);

                $guild->channels->save($newChannel)->done(function () use ($guild, $channel, $newChannel) {
                    /** @var Member $member */
                    foreach ($channel->guild->members as $member) {
                        $allow = [];
                        $deny = [];
                        $permissions = ['priority_speaker', 'stream', 'connect', 'speak', 'mute_members', 'deafen_members', 'move_members', 'use_vad', 'add_reactions', 'send_messages', 'send_tts_messages', 'embed_links', 'attach_files', 'read_message_history', 'mention_everyone', 'use_external_emojis', 'kick_members', 'ban_members', 'administrator', 'manage_guild', 'view_audit_log', 'view_guild_insights', 'change_nickname', 'manage_nicknames', 'manage_emojis', 'create_instant_invite', 'manage_channels', 'view_channel', 'manage_roles', 'manage_webhooks'];
                        $_ = $member->getPermissions();
                        foreach ($permissions as $permission) {
                            if ($_->{$permission} == true) {
                                $allow[] = $permission;
                            }
                            else {
                                $deny[] = $permission;
                            }
                        }
                        $newChannel->setPermissions($member, $allow, $deny);
                    }

                    sleep(3);
                    $guild->channels->delete($channel)->done(function () use ($newChannel) {
                        $newChannel->sendMessage('The channel "' . $newChannel->name . '" is cleared.');
                    });
                });
            });
        }
        else {
            $message = $this->message;

            $this->message->reply($this->message->channel->name . ' is clearing...')->then(function () use ($message) {
                $channel = $message->channel;
                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                        $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                            $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                        $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                            $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                                $message->channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages) use ($channel, $message) {
                                                    $channel->deleteMessages($messages)->done(function () use ($channel, $message) {
                                                        $channel->sendMessage('Channel is cleared.')->done(function (Message $message) {
                                                            $message->delayedDelete(1500);
                                                        });
                                                    });
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        }
    }
}
