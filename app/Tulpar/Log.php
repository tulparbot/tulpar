<?php


namespace App\Tulpar;


use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;

class Log
{
    private static Channel|null|false $channel = null;

    private static function getChannel(): Channel|null
    {
        if (static::$channel === false) {
            return null;
        }

        if (static::$channel === null) {
            static::$channel = Tulpar::getInstance()->getLogChannel();
            if (static::$channel === null) {
                static::consoleLog('Log channel is not selected');
                static::$channel = false;
                return null;
            }
        }

        return static::$channel;
    }

    private static function consoleLog(string $text, string $level = 'WARN'): void
    {
        echo PHP_EOL . PHP_EOL . '!!! ' . $level . ': ' . $text . ' !!!' . PHP_EOL . PHP_EOL;
    }

    public static function emergency($message, array $context = [])
    {
        static::log('emergency', $message, $context);
    }

    public static function alert($message, array $context = [])
    {
        static::log('alert', $message, $context);
    }

    public static function critical($message, array $context = [])
    {
        static::log('critical', $message, $context);
    }

    public static function error($message, array $context = [])
    {
        static::log('error', $message, $context);
    }

    public static function warning($message, array $context = [])
    {
        static::log('warning', $message, $context);
    }

    public static function notice($message, array $context = [])
    {
        static::log('notice', $message, $context);
    }

    public static function info($message, array $context = [])
    {
        static::log('info', $message, $context);
    }

    public static function debug($message, array $context = [])
    {
        static::log('debug', $message, $context);
    }

    public static function discordLog($level, $message, array $context = [])
    {
        try {
            if ($message instanceof Exception) {
                $message = ('File: ' . $message->getFile() . ' Code: ' . $message->getCode() . ' Line: ' . $message->getLine() . ' Message: ' . $message->getMessage());
            }

            $channel = static::getChannel();
            if ($channel !== false) {
                if (!Tulpar::getInstance()->checkPermission($channel->guild, 'administrator', true)) {
                    static::consoleLog('Missing "administrator" permission in application server', 'ERR');
                    Tulpar::getInstance()->stop();
                    exit;
                }

                if (in_array($level, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'])) {
                    if (config('tulpar.server.logging.' . $level) == true) {
                        $messages = explode("\r\n", chunk_split($message, 400));
                        $channel->sendMessage('``' . strtoupper($level) . '``: ' . $messages[0])->done(function (Message $message) use ($messages) {
                            $messages = collect($messages)->except(0)->toArray();
                            foreach ($messages as $msg) {
                                if (mb_strlen($msg) > 0) {
                                    $message->reply($msg);
                                }
                            }
                        });
                    }
                }
            }
        } catch (Exception $exception) {
            FacadesLog::critical($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
            static::consoleLog('ERR', 'CANNOT SEND MESSAGE TO LOG CHANNEL SEE LOG FILE TO DETAILS');
        }
    }

    public static function log($level, $message, array $context = [])
    {
        if ($message instanceof Exception) {
            FacadesLog::critical($message->getTraceAsString());
        }
        else if (mb_strlen($message) < 1) {
            return;
        }

        static::discordLog($level, $message, $context);
        FacadesLog::log($level, $message, $context);
    }
}
