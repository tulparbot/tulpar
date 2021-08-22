<?php

namespace App\Extensions;

class FileStore extends \Illuminate\Cache\FileStore
{
    /**
     * Get path for our keys store
     *
     * @return string
     */
    private function keysPath()
    {
        return storage_path(implode(DIRECTORY_SEPARATOR, ['framework', 'cache', 'keys.json']));
    }

    /**
     * Get all keys from our store
     *
     * @return array
     */
    public function getKeys()
    {
        if (!file_exists($this->keysPath())) {
            return [];
        }

        return json_decode(file_get_contents($this->keysPath()), true) ?? [];
    }

    /**
     * Save all keys to file
     *
     * @param array $keys
     * @return bool
     */
    private function saveKeys($keys)
    {
        return file_put_contents($this->keysPath(), json_encode($keys)) !== false;
    }

    /**
     * Store a key in our store
     *
     * @param string $key [description]
     */
    private function addKey($key)
    {
        $keys = $this->getKeys();

        // Don't add duplicate keys into our store
        if (!in_array($key, $keys)) {
            $keys[] = $key;
        }

        $this->saveKeys($keys);
    }

    // -------------------------------------------------------------------------
    // LARAVEL METHODS
    // -------------------------------------------------------------------------

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $seconds
     * @return bool
     */
    public function put($key, $value, $seconds)
    {
        $this->addKey($key);
        return parent::put($key, $value, $seconds);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function forget($forgetKey, $seperator = '.')
    {
        // Get all stored keys
        $storedKeys = $this->getKeys();

        // This value will be returned as true if we match at least 1 key
        $keyFound = false;

        foreach ($storedKeys as $i => $storedKey) {
            // Only proceed if stored key starts with OR matches forget key
            if (!str_starts_with($storedKey, $forgetKey . $seperator) && $storedKey != $forgetKey) {
                continue;
            }

            // Set to return true after all processing
            $keyFound = true;

            // Remove key from our records
            unset($storedKeys[$i]);

            // Remove key from the framework
            parent::forget($storedKey);
        }

        // Update our key list
        $this->saveKeys($storedKeys);

        // Return true if at least 1 key was found
        return $keyFound;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        $this->saveKeys([]);
        return parent::flush();
    }
}
