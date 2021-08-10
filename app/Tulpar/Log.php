<?php


namespace App\Tulpar;


use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;

class Log
{
    public static function emergency($message, array $context = [])
    {
        static::log('emergency', $message);
    }

    public static function alert($message, array $context = [])
    {
        static::log('alert', $message);
    }

    public static function critical($message, array $context = [])
    {
        static::log('critical', $message);
    }

    public static function error($message, array $context = [])
    {
        static::log('error', $message);
    }

    public static function warning($message, array $context = [])
    {
        static::log('warning', $message);
    }

    public static function notice($message, array $context = [])
    {
        static::log('notice', $message);
    }

    public static function info($message, array $context = [])
    {
        static::log('info', $message);
    }

    public static function debug($message, array $context = [])
    {
        static::log('debug', $message);
    }

    public static function log($level, $message, array $context = [])
    {
        FacadesLog::log($level, $message, $context);
        try {
            $channel = Tulpar::getInstance()->getLogChannel();
            if ($channel === null) {
                echo PHP_EOL . PHP_EOL . '!!! WARN: Log channel is not selected !!!' . PHP_EOL . PHP_EOL;
            }
            else {
                if (!Tulpar::getInstance()->checkPermission($channel->guild, 'administrator', true)) {
                    echo PHP_EOL . PHP_EOL . '!!! ERR: Missing "administrator" permission in application server !!!' . PHP_EOL . PHP_EOL;
                    Tulpar::getInstance()->getDiscord()->getLoop()->stop();
                    exit;
                }

                if (in_array($level, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'])) {
                    if (config('tulpar.server.logging.' . $level) == true) {
                        $channel->sendMessage(
                            '``' . strtoupper($level) . '``: ' . $message
                        );
                    }
                }
            }
        } catch (Exception $exception) {
            // ...
        }
    }
}
