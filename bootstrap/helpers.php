<?php

use App\Tulpar\Translator;
use Discord\Parts\Guild\Guild;

if (!function_exists('__translate')) {
    /**
     * @param string       $translation
     * @param Guild|string $locale
     * @param array        $replacements
     * @return string
     */
    function __translate(string $translation, Guild|string $locale = 'en', array $replacements = []): string
    {
        if ($locale instanceof Guild) {
            $locale = $locale->preferred_locale;
        }

        return Translator::translate($translation, $locale, $replacements);
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
