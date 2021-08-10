<?php


namespace App\Tulpar;


use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;

class Log
{
    public static function emergency($message, array $context = array())
    {
        static::log('emergency', $message);
    }

    public static function alert($message, array $context = array())
    {
        static::log('alert', $message);
    }

    public static function critical($message, array $context = array())
    {
        static::log('critical', $message);
    }

    public static function error($message, array $context = array())
    {
        static::log('error', $message);
    }

    public static function warning($message, array $context = array())
    {
        static::log('warning', $message);
    }

    public static function notice($message, array $context = array())
    {
        static::log('notice', $message);
    }

    public static function info($message, array $context = array())
    {
        static::log('info', $message);
    }

    public static function debug($message, array $context = array())
    {
        static::log('debug', $message);
    }

    public static function log($level, $message, array $context = array())
    {
        FacadesLog::log($level, $message, $context);
        try {
            Tulpar::getInstance()->getLogChannel()?->sendMessage(
                '``' . strtoupper($level) . '``: ' . $message
            );
        } catch (Exception $exception) {
            // ...
        }
    }
}
