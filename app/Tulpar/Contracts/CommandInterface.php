<?php


namespace App\Tulpar\Contracts;


use Discord\Discord;
use Discord\Parts\Channel\Message;

interface CommandInterface
{
    /**
     * Get the command name of command class.
     *
     * @return string
     */
    public static function getCommand(): string;

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string;

    /**
     * Get the command version.
     *
     * @return string
     */
    public static function getVersion(): string;

    /**
     * The required argument keys.
     *
     * @return array
     */
    public static function getRequires(): array;

    /**
     * The required permissions.
     *
     * @return array
     */
    public static function getPermissions(): array;

    /**
     * Get example valid usage of the command.
     *
     * @return string
     */
    public static function getUsages(): string;

    /**
     * Get help of the command.
     *
     * @return string
     */
    public static function getHelp(): string;

    /**
     * Check if this command are allowed in private channel.
     *
     * @return bool
     */
    public static function isAllowedPm(): bool;

    /**
     * The Tulpar Bot Command Constructor.
     *
     * @param Message $message
     * @param Discord $discord
     */
    public function __construct(Message $message, Discord $discord);

    /**
     * Check command access.
     *
     * @param bool $messages
     * @return bool
     */
    public function checkAccess(bool $messages = false): bool;

    /**
     * Check command ready to use.
     *
     * @return string
     */
    public function check(): string;

    /**
     * Execute the command.
     */
    public function run(): void;
}
