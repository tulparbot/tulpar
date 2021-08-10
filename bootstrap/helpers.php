<?php

use App\Tulpar\Translator;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Str;

if (! function_exists('__translate')) {
    /**
     * @param string $translation
     * @param Guild|string $locale
     * @param array $replacements
     * @return string
     */
    function __translate(string $translation, Guild|string $locale = 'en', array $replacements = []): string {
        if ($locale instanceof Guild) {
            $locale = $locale->preferred_locale;
        }

        return Translator::translate($translation, $locale, $replacements);
    }
}
