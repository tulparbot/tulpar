<?php


namespace App\Tulpar\Commands;


use App\Enums\CommandValidation;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Tulpar;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use IsaEken\Strargs\Strargs;

class BaseCommand implements CommandInterface
{
    public static string $command = '';

    public static string $description = 'Tulpar Base Command';

    public static string $version = 'v1.0';

    public static array $requires = [];

    public static array $permissions = ['root'];

    public static array $usages = [''];

    public static bool $allowPm = false;

    /**
     * @inheritDoc
     */
    public static function getCommand(): string
    {
        return static::$command;
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return static::$description;
    }

    /**
     * @inheritDoc
     */
    public static function getVersion(): string
    {
        return static::$version;
    }

    /**
     * @inheritDoc
     */
    public static function getRequires(): array
    {
        return static::$requires ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function getPermissions(): array
    {
        return static::$permissions ?? ['root'];
    }

    /**
     * @inheritDoc
     */
    public static function getUsages(): string
    {
        $prefix = Tulpar::getPrefix();
        $command = static::getCommand();
        $usages = '';

        $loop = 0;
        foreach (static::$usages as $usage) {
            $loop++;
            $usages .= $prefix . $command . ' ' . $usage;

            if (count(static::$usages) > $loop) {
                $usages .= PHP_EOL;
            }
        }

        return $usages;
    }

    /**
     * @inheritDoc
     */
    public static function getHelp(): string
    {
        $command = static::getCommand();
        $description = static::getDescription();
        $version = static::getVersion();
        $usages = static::getUsages();

        return <<<HELP
$command ($version)
$description

``$usages``
HELP;
    }

    /**
     * @inheritDoc
     */
    public static function isAllowedPm(): bool
    {
        return static::$allowPm;
    }

    /**
     * The command parser.
     *
     * @var Strargs $userCommand
     */
    public Strargs $userCommand;

    /**
     * @inheritDoc
     */
    public function __construct(
        public Message $message,
        public Discord $discord,
    )
    {
        $this->userCommand = new Strargs(substr($this->message->content, mb_strlen(Tulpar::getPrefix())));
        $this->userCommand->decode();
    }

    /**
     * @inheritDoc
     * @todo make this
     */
    public function checkAccess(bool $messages = false): bool
    {
        return true;

        if (Helpers::isRoot($this->message->user)) {
            return true;
        }

        $permissions = $this->message->member->getPermissions()->getRawAttributes();
        foreach (static::getPermissions() as $permission) {
            if ($permission == 'root') {
                if ($messages == true) {
                    $this->message->channel->sendMessage('nabıyon aq :D?');
                }

                return false;
            }

            if ($permissions[$permission] == false) {
                if ($messages == true) {
                    $this->message->channel->sendMessage('You are not authorized to use this command.');
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function check(): string
    {
        if (str_starts_with($this->message->content, Tulpar::getPrefix())) {
            if (mb_strtolower($this->userCommand->getCommand()) == mb_strtolower(static::getCommand())) {

                if (!$this->checkAccess(true)) {
                    return CommandValidation::NoAccess;
                }

                foreach (static::getRequires() as $require) {
                    if (!$this->userCommand->hasArgument($require)) {
                        return CommandValidation::InvalidArguments;
                    }
                }

                return CommandValidation::Success;
            }
        }

        return CommandValidation::Unknown;
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        // ...
    }
}
