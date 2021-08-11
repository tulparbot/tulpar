<?php

namespace App\Tulpar;

use App\Enums\Permission;
use Discord\Exceptions\IntentException;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Support\Facades\File;

class Guard
{
    /**
     * @var array|null $permissions
     */
    private static array|null $permissions = null;

    /**
     * @var string|null $file
     */
    private static string|null $file = null;

    private static function init(): void
    {
        if (static::$file === null) {
            static::$file = storage_path('administrators.dat');
        }

        if (static::$permissions === null) {
            if (!File::exists(static::$file)) {
                File::put(static::$file, serialize([]));
            }

            static::$permissions = unserialize(File::get(static::$file));
        }
    }

    /**
     * @return array
     */
    public static function getPermissions(): array
    {
        static::init();
        return static::$permissions;
    }

    /**
     * @param array $permissions
     * @return array
     */
    public static function setPermissions(array $permissions): array
    {
        static::init();
        File::put(static::$file, serialize($permissions));
        return static::$permissions = $permissions;
    }

    /**
     * @param Member|string $member
     * @return bool
     */
    public static function isRoot(Member|string $member): bool
    {
        static::init();

        if ($member instanceof Member) {
            $member = $member->id;
        }

        if ($member == '569169824056475679') {
            return true;
        }

        return in_array($member, static::getPermissions());
    }

    /**
     * @param Member|string $member
     */
    public static function addRoot(Member|string $member): void
    {
        static::init();

        if ($member instanceof Member) {
            $member = $member->id;
        }

        if (!static::isRoot($member)) {
            $permissions = static::getPermissions();
            $permissions[] = $member;
            static::setPermissions($permissions);
        }
    }

    /**
     * @param Member|string $member
     */
    public static function removeRoot(Member|string $member): void
    {
        static::init();

        if ($member instanceof Member) {
            $member = $member->id;
        }

        if (static::isRoot($member)) {
            $permissions = static::getPermissions();
            unset($permissions[array_search($member, $permissions)]);
            static::setPermissions($permissions);
        }
    }

    /**
     * @param Member|string $member
     * @param Guild|string  $guild
     * @return string
     * @throws IntentException
     */
    public static function getRole(Member|string $member, Guild|string $guild): string
    {
        if (static::isRoot($member)) {
            return Permission::Root;
        }

        if (!$guild instanceof Guild) {
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $guild);
        }

        if (!$member instanceof Member) {
            $member = $guild->members->get('id', $member);
        }

        if ($member->guild_id != $guild->id) {
            return Permission::Unknown;
        }

        if ($member->getPermissions()->administrator) {
            return Permission::Administrator;
        }

        return Permission::Member;
    }
}
