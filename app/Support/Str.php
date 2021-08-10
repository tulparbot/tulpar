<?php

namespace App\Support;

class Str extends \Illuminate\Support\Str
{
    public static function onlyFirsts($value)
    {
        $words = explode(' ', $value);
        $text = '';

        foreach ($words as $word) {
            $text .= $word[0];
        }

        return $text;
    }
}
