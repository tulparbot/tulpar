<?php

namespace App\Tulpar\CommandTraits;

use Discord\Exceptions\IntentException;
use ReflectionClass;

trait HasEnumArgument
{
    /**
     * @param int|string  $argument
     * @param string      $enum
     * @param string|null $default
     * @param bool        $failMessage
     * @return string|null
     * @throws IntentException
     */
    public function getEnumArgument(int|string $argument, string $enum, string|null $default = null, bool $failMessage = false): string|null
    {
        if (!class_exists($enum)) {
            return null;
        }

        $constants = (new ReflectionClass($enum))->getConstants();
        if ($this->userCommand->hasArgument($argument)) {
            $argument = $this->userCommand->getArgument($argument);

            foreach ($constants as $constant => $value) {
                if ($value == $argument) {
                    return $argument;
                }
            }

            if ($failMessage) {
                $this->message->reply($this->translate('You can only use: :constants', [
                    'constants' => implode(', ', $constants),
                ]));
            }

            return null;
        }

        return $default;
    }
}
