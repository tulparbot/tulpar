<?php


namespace App\Tulpar;


use Discord\Exceptions\IntentException;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Str;

class Translator
{
    /**
     * @var array $translations
     */
    private static array $translations = [];

    /**
     * @param Guild|string $guild
     * @return string
     * @throws IntentException
     */
    public static function localeBy(Guild|string $guild): string
    {
        if (!$guild instanceof Guild) {
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $guild);
        }

        return $guild->preferred_locale;
    }

    /**
     * @param string $text
     * @param array  $replacements
     * @return string
     */
    public static function format(string $text, array $replacements = []): string
    {
        $keys = collect(array_keys($replacements))->map(fn ($replacement) => ':' . $replacement)->toArray();
        $values = collect(array_values($replacements))->toArray();

        return Str::replace($keys, $values, $text);
    }

    /**
     * @param string $translation
     * @param string $locale
     * @param array  $replacements
     * @return string
     */
    public static function translate(string $translation, string $locale = 'en', array $replacements = []): string
    {
        $replacements['locale'] = $locale;
        $translation_file = resource_path('lang/' . $locale . '.json');

        if (file_exists($translation_file)) {
            if (!array_key_exists($translation, static::$translations)) {
                static::$translations = json_decode(file_get_contents(resource_path('lang/' . $locale . '.json')), true);
            }

            if (array_key_exists($translation, static::$translations)) {
                return static::format(static::$translations[$translation], $replacements);
            }
        }

        return static::format($translation, $replacements);
    }
}
