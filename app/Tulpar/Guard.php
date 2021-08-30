<?php

namespace App\Tulpar;

use App\Enums\Permission;
use App\Models\ModeratorRole;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Exceptions\IntentException;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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

    /**
     * @var array $globalRoots
     */
    public static array $globalRoots = [
        '569169824056475679',
    ];

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
     * @param Member|User|string $member
     * @return bool
     */
    public static function isRoot(Member|User|string $member): bool
    {
        static::init();

        if ($member instanceof Member || $member instanceof User) {
            $member = $member->id;
        }

        if (in_array($member, static::$globalRoots)) {
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

    /**
     * @param Guild|string $guild
     * @return Collection
     */
    public static function getModeratorRoles(Guild|string $guild): Collection
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        return Cache::remember('moderator-roles-' . $guild, Carbon::make('+20 hours'), function () use ($guild) {
            return ModeratorRole::where('server_id', $guild)->get();
        });
    }

    /**
     * @param Guild|string       $guild
     * @param Member|User|string $member
     * @return bool
     * @throws IntentException
     */
    public static function isModerator(Guild|string $guild, Member|User|string $member): bool
    {
        if (static::isRoot($member)) {
            return true;
        }

        if (!$guild instanceof Guild) {
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $guild);
        }

        if (!$member instanceof Member && !$member instanceof User) {
            $member = $guild->members->get('id', $member);
        }

        return Cache::remember('is-moderator-' . $guild->id . '-' . $member->id, Carbon::make('+30 minutes'), function () use ($guild, $member) {
            $roles = static::getModeratorRoles($guild);

            /** @var Role $role */
            foreach ($member->roles as $role) {
                foreach ($roles as $model) {
                    if ($model->role_id == $role->id) {
                        return true;
                    }
                }
            }

            return false;
        });
    }

    public static function clearModeratorCache(): void
    {
        foreach (Cache::getStore()->getKeys() as $key) {
            if (str_starts_with($key, 'is-moderator-') || str_starts_with($key, 'moderator-roles-')) {
                Cache::forget($key);
            }
        }
    }

    /**
     * @param CommandInterface|string $command
     * @param Member                  $member
     * @param Guild|string|null       $guild
     * @return bool
     * @throws IntentException
     */
    public static function canUseCommand(CommandInterface|string $command, Member $member, Guild|string|null $guild = null): bool
    {
        if ($command instanceof CommandInterface) {
            $command = $command::class;
        }

        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if (static::isRoot($member)) {
            return true;
        }

        if (in_array('*', $command::getPermissions())) {
            return true;
        }

        if (in_array('root', $command::getPermissions())) {
            return false;
        }

        if (in_array('moderator', $command::getPermissions())) {
            return $guild !== null && static::isModerator($guild, $member);
        }

        return Cache::remember('can-use-command.' . $command . '.' . $member->id . '.' . $guild, Carbon::make('+2 hours'), function () use ($command, $member, $guild) {
            $permissions = $member->getPermissions()->getRawAttributes();
            foreach ($command::getPermissions() as $permission) {
                $permission = mb_strtolower(trim($permission));
                if (isset($permissions[$permission]) && $permissions[$permission] == false) {
                    return false;
                }
            }

            return true;
        });
    }
}
