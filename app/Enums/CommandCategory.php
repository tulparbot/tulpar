<?php

namespace App\Enums;

use ReflectionClass;

abstract class CommandCategory
{
    const General = 'general';
    const Authorization = 'authorization';
    const Chat = 'chat';
    const Development = 'development';
    const Game = 'game';
    const Management = 'management';
    const Moderation = 'moderation';
    const Music = 'music';
    const Rank = 'rank';
    const Birthdays = 'birthdays';
    const Other = 'other';

    /**
     * @return array
     */
    public static function getCategories(): array
    {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }

    /**
     * @return array
     */
    public static function getCommands(): array
    {
        $commands = [];
        foreach (config('tulpar.commands', []) as $command) {
            if (!isset($commands[$command::getCategory()])) {
                $commands[$command::getCategory()] = [];
            }

            $commands[$command::getCategory()][] = $command;
        }

        return $commands;
    }
}
