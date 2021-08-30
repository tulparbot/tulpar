<?php


namespace App\Tulpar;


use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;

/**
 * @method static void emergency($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void log($level, $message, array $context = [])
 */
class Logger
{
    /**
     * @var Monolog|null $logger
     */
    private static Monolog|null $logger = null;

    /**
     * @return LoggerInterface
     */
    public static function getLogger(): LoggerInterface
    {
        if (static::$logger === null) {
            static::$logger = new Monolog(config('app.name'));
            static::$logger->pushHandler(new StreamHandler('php://stdout', Monolog::toMonologLevel(env('LOG_LEVEL', 'debug'))));
        }

        return static::$logger;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if (in_array($name, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug', 'log'])) {
            static::getLogger()->$name(...$arguments);
            return;
        }

        return $name(...$arguments);
    }
}
