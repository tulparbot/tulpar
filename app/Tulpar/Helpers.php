<?php


namespace App\Tulpar;


use App\Enums\Align;
use Closure;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;
use Exception;
use React\Promise\PromiseInterface;

class Helpers
{
    /**
     * @param callable|Closure $callable
     * @param ...$arguments
     * @return mixed
     */
    public static function call(callable|Closure $callable, ...$arguments): mixed
    {
        try {
            return $callable(...$arguments);
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            return $exception;
        }
    }

    /**
     * @param string $line
     * @param string $align
     * @param int $length
     * @return string
     */
    public static function line(string $line, string $align = Align::Left, int $length = 40): string
    {
        $string = '';

        if ($align == Align::Right) {
            $__length = $length - mb_strlen($line);
            $string .= str_repeat(' ', $__length);
            $string .= $line;
        } else if ($align == Align::Center) {
            $__length = (($length - mb_strlen($line)) / 2);
            $string .= str_repeat(' ', $__length);
            $string .= $line;
            $string .= str_repeat(' ', $__length);
        } else {
            $__length = $length - mb_strlen($line);
            $string .= $line;
            $string .= str_repeat(' ', $__length);
        }

        if (mb_strlen($string) < $length) {
            $string .= ' ';
        }

        return $string;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function userTag(string $id): string
    {
        return "<@$id>";
    }

    /**
     * @param User|Member|string $user
     * @return bool
     */
    public static function isRoot(User|Member|string $user): bool
    {
        $id = $user instanceof User ? $user->id : ($user instanceof Member ? $user->user->id : $user);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", file_get_contents(base_path('administrators.txt'))) as $administrator) {
            if ($administrator == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User|string $user
     * @param Guild $guild
     * @param callable $if
     * @param callable|null $else
     * @return PromiseInterface
     * @throws Exception
     */
    public static function whenAdmin(User|string $user, Guild $guild, callable $if, callable $else = null): PromiseInterface
    {
        return static::hasPermission($user, $guild, $if, $else);
    }

    /**
     * @param User|string $user
     * @param Guild $guild
     * @param callable $if
     * @param callable|null $else
     * @param string $permission
     * @return PromiseInterface
     * @throws Exception
     */
    public static function hasPermission(User|string $user, Guild $guild, callable $if, callable $else = null, string $permission = 'administrator'): PromiseInterface
    {
        return $guild->members
            ->fetch($user instanceof User ? $user->id : $user)
            ->then(function ($user) use ($if, $else, $permission) {
                if ($user->getPermissions()->$permission) {
                    $if();
                } else {
                    $else();
                }
            });
    }
}
