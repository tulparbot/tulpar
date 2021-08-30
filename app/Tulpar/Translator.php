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

        if ($locale == 'en' && !file_exists($translation_file)) {
            file_put_contents($translation_file, json_encode([]));
        }

        if (!array_key_exists($locale, static::$translations)) {
            static::$translations[$locale] = [];
        }

        if (array_key_exists($translation, static::$translations[$locale]) && config('app.env') === 'production') {
            return static::format(static::$translations[$locale][$translation], $replacements);
        }

        if (file_exists($translation_file)) {
            static::$translations[$locale] = json_decode(file_get_contents($translation_file), true, flags: JSON_UNESCAPED_UNICODE);

            if (!array_key_exists($translation, static::$translations[$locale])) {
                static::$translations[$locale][$translation] = $translation;
                file_put_contents($translation_file, json_encode(static::$translations[$locale], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                return static::translate($translation, $locale, $replacements);
            }

            return static::format(static::$translations[$locale][$translation], $replacements);
        }

        return static::format($translation, $replacements);
    }
}
