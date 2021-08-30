<?php

use App\Tulpar\Translator;
use Discord\Exceptions\IntentException;
use Discord\Parts\Guild\Guild;

if (!function_exists('_text')) {
    /**
     * @param Guild|string|null $guild
     * @param string            $translation
     * @param array             $replacements
     * @return string
     * @throws IntentException
     */
    function _text(Guild|string|null $guild, string $translation, array $replacements = []): string
    {
        if ($guild === null) {
            $guild = 'en';
        }

        return Translator::translate($translation, Translator::localeBy($guild), $replacements);
    }
}

if (!function_exists('bytesToHuman')) {
    /**
     * @param int|float $bytes
     * @return string
     */
    function bytesToHuman(int|float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
