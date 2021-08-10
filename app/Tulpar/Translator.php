<?php


namespace App\Tulpar;


use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Str;

class Translator
{
    /**
     * @var array $translations
     */
    private static array $translations = [];

    /**
     * @param Message|Guild $from
     * @param Discord       $discord
     * @return string
     */
    public static function findLocale(Message|Guild $from, Discord $discord): string
    {
        if ($from instanceof Message) {
            $guild = $from->channel->guild;
        }
        else {
            $guild = $from;
        }

        if ($guild !== null) {
            return $guild->preferred_locale;
        }

        return 'en';
    }

    public static function translate(string $translation, string $locale = 'en', array $replacements = []): string
    {
        $translation_file = resource_path('lang/' . $locale . '.json');
        if (file_exists($translation_file)) {
            if (!array_key_exists($translation, static::$translations)) {
                static::$translations = json_decode(file_get_contents(resource_path('lang/' . $locale . '.json')), true);
            }

            if (array_key_exists($translation, static::$translations)) {
                return Str::replace(array_keys($replacements), array_values($replacements), static::$translations[$translation]);
            }
        }

        return Str::replace(array_keys($replacements), array_values($replacements), $translation);
    }
}
