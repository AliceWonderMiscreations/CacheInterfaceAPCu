<?php
declare(strict_types = 1);

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu
 *
 * @package AWonderPHP\SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/SimpleCacheAPCu
 */
/*
 +-------------------------------------------------------+
 |                                                       |
 | Copyright (c) 2018 Alice Wonder Miscreations          |
 |  May be used under terms of MIT license               |
 |                                                       |
 +-------------------------------------------------------+
 | Purpose: PSR-16 APCu Interface                        |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu;

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu
 *
 * This class implements the [PHP-FIG PSR-16](https://www.php-fig.org/psr/psr-16/)
 *  interface for a cache class.
 *
 * It needs PHP 7.1 or newer and obviously the [APCu PECL](https://pecl.php.net/package/APCu) extension.
 *  I am not sure of the minimum APCu version, I am using 5.1.9 myself at the moment.
 */
class SimpleCacheAPCu implements \Psr\SimpleCache\CacheInterface
{
    /** @var bool */
    protected $enabled = false;
    /** @var bool */
    protected $strictType = false;
    /** @var string */
    protected $salt = '6Dxypt3ePw2SM2zYzEVAFkDBQpxbk16z1';
    /** @var string */
    protected $webappPrefix = 'DEFAULT_';
    /** @var int */
    protected $defaultSeconds = 0;

    /**
     * Checks whether or not a parameter is of the iterable pseudo-type
     *
     * @param mixed $arg The parameter to be checked
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException::typeNotIterable($arg)
     *
     * @return void
     */
    protected function checkIterable($arg): void
    {
        if (! is_iterable($arg)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::typeNotIterable($arg);
        }
    }

    /**
     * Creates hash substring to use in internal cache key.
     *
     * This class obfuscates the user supplied cache keys by using a substring
     * of the hex representation of a hash of that key. This function creates
     * the hex representation of the hash and grabs a 16 character substring.
     *
     * @param string $key The user defined key to hash
     *
     * @return string
     */
    protected function weakHash($key): string
    {
        $key = $this->salt . $key;
        $key = hash('ripemd160', $key);
        // 16^16 should be enough of the hash to avoid collisions
        return substr($key, 17, 16);
    }

    /**
     * Takes user supplied key and creates internal cache key.
     *
     * This function takes the user defined cache key, gets the substring of a
     * hash from the weakHash function, and appends that substring to the WebApp
     * prefix string to create the actual key that will be used with APCu.
     *
     * @param string $key The user defined cache key to hash
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     *
     * @return string
     */
    protected function adjustKey($key): string
    {
        if (! $this->strictType) {
            $invalidTypes = array('array', 'object', 'boolean', 'NULL');
            $type = gettype($key);
            if (! in_array($type, $invalidTypes)) {
                $key = (string)$key;
            }
        }
        if (! is_string($key)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::keyTypeError($key);
        }
        $key = trim($key);
        if (strlen($key) === 0) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::emptyKey();
        }
        if (strlen($key) > 255) {
            // key should not be larger
            //  than 255 character
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::keyTooLong($key);
        }
        if (preg_match('/[\[\]\{\}\(\)\/\@\:\\\]/', $key) !== 0) {
            // PSR-16 says those characters not allowed
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::invalidKeyCharacter($key);
        }
        $key = $this->webappPrefix . $this->weakHash($key);
        return $key;
    }

    /**
     * Sets the prefix (namespace) for the internal keys
     *
     * @param string $str The string to use as internal key prefix.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     *
     * @return void
     */
    protected function setWebAppPrefix($str): void
    {
        $type = gettype($str);
        if (! is_string($str)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::cstrTypeError($str, 'WebApp Prefix');
        }
        $str = strtoupper(trim($str));
        if (strlen($str) < 3) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::webappPrefixTooShort($str);
        }
        if (strlen($str) > 32) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::webappPrefixTooLong($str);
        }
        if (preg_match('/[^A-Z0-9]/', $str) !== 0) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::webappPrefixNotAlphaNumeric($str);
        }
        $this->webappPrefix = $str . '_';
    }

    /**
     * Sets the salt to use when generating the internal keys
     *
     * @param string $str The string to use as the salt when creating the internal key prefix.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     *
     * @return void
     */
    protected function setHashSalt($str): void
    {
        $type = gettype($str);
        if (! is_string($str)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::cstrTypeError($str, 'Salt');
        }
        $str = trim($str);
        if (strlen($str) < 8) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::saltTooShort($str);
        }
        $this->salt = $str;
    }

    /**
     * Generates Time To Live parameter to use with APCu.
     *
     * This function takes either NULL, an integer or a string. When supplied
     * with an integer, if it is less than the current seconds from UNIX Epoch
     * it is treated as desired seconds the record should last. If it is larger
     * it assumed it is an expiration time and then calculates the corresponding
     * TTL. When fed a string, the `strtotime()` function is used to turn the
     * string into a UNIX seconds from Epoch expiration, and it then calculates
     * the corresponding TTL. When fed NULL, it uses the class default TTL.
     *
     * @param null|int|string $ttl The length to cache or the expected expiration.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return int
     */
    protected function ttlToSeconds($ttl): int
    {
        if (is_null($ttl)) {
            return $this->defaultSeconds;
        }
        if (! $this->strictType) {
            if (is_numeric($ttl)) {
                $ttl = intval($ttl, 10);
            }
        }
        $type = gettype($ttl);
        if (! in_array($type, array('integer', 'string'))) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::ttlTypeError($ttl);
        }
        $now = time();
        if (is_int($ttl)) {
            $seconds = $ttl;
            if ($seconds > $now) {
                return ($seconds - $now);
            }
            if ($seconds < 0) {
                throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::negativeTTL($seconds);
            }
            return $seconds;
        }
        // hope it is a date string
        if ($seconds = strtotime($ttl, $now)) {
            if ($seconds > $now) {
                return ($seconds - $now);
            } else {
                throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::dateStringInPast($ttl);
            }
        }
        throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::invalidTTL($ttl);
    }

    /**
     * A wrapper for the actual fetch from the cache
     *
     * @param string $realKey The internal key used with APCu
     * @param mixed  $default The value to return if a cache miss
     *
     * @return mixed The value in the cached key => value pair, or $default if a cache miss
     */
    protected function cacheFetch($realKey, $default)
    {
        $return = apcu_fetch($realKey, $success);
        if ($success) {
            return $return;
        }
        return $default;
    }

    /**
     * A wrapper for the actual store of key => value pair in the cache
     *
     * @param string          $realKey The internal key used with APCu
     * @param mixed           $value   The value to be stored
     * @param null|int|string $ttl     The TTL value of this item. If no value is sent
     *                                 and the driver supports TTL then the library may
     *                                 set a default value for it or let the driver
     *                                 take care of that.
     *
     * @return bool Returns True on success, False on failure
     */
    protected function cacheStore($realKey, $value, $ttl): bool
    {
        $seconds = $this->ttlToSeconds($ttl);
        return apcu_store($realKey, $value, $seconds);
    }

    /**
     * A wrapper for the actual delete of a key => value pair in the cache
     *
     * @param string $realKey The key for the key => value pair to be removed from the cache
     *
     * @return bool Returns True on success, False on failure
     */
    protected function cacheDelete($realKey): bool
    {
        return apcu_delete($realKey);
    }

    /**
     * Sets the default cache TTL in seconds.
     *
     * @param int $seconds The default seconds to cache entries
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     *
     * @return void
     */
    public function setDefaultSeconds($seconds): void
    {
        if (! $this->strictType) {
            if (is_numeric($seconds)) {
                $seconds = intval($seconds);
            }
        }
        if (! is_int($seconds)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::defaultTTL($seconds);
        }
        if ($seconds < 0) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::negativeDefaultTTL($seconds);
        }
        $this->defaultSeconds = $seconds;
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default (optional) Default value to return if the key does not exist.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)
    {
        $realKey = $this->adjustKey($key);
        if ($this->enabled) {
            return $this->cacheFetch($realKey, $default);
        }
        return $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string          $key   The key of the item to store.
     * @param mixed           $value The value of the item to store, must be serializable.
     * @param null|int|string $ttl   (optional) The TTL value of this item. If no value is sent and
     *                               the driver supports TTL then the library may set a default value
     *                               for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function set($key, $value, $ttl = null): bool
    {
        $realKey = $this->adjustKey($key);
        if ($this->enabled) {
            return $this->cacheStore($realKey, $value, $ttl);
        }
        return false;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete($key): bool
    {
        if ($this->enabled) {
            $realKey = $this->adjustKey($key);
            return $this->cacheDelete($realKey);
        }
        return false;
    }

    /**
     * Wipes clean the entire cache's keys. This implementation only wipes for matching
     * webappPrefix (custom NON PSR-16 feature set during constructor)
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        if ($this->enabled) {
            if (class_exists('\APCUIterator', false)) {
                $iterator = new \APCUIterator('#^' . $this->webappPrefix . '#', APC_ITER_KEY);
                foreach ($iterator as $item) {
                    $key = $item['key'];
                    apcu_delete($key);
                }
                return true;
            }
            $info = apcu_cache_info();
            if (isset($info['cache_list'])) {
                $return = true;
                $cachelist = $info['cache_list'];
                foreach ($cachelist as $item) {
                    if (isset($item['info'])) {
                        $key = $item['info'];
                        if (strpos($key, $this->webappPrefix) === 0) {
                            apcu_delete($key);
                        }
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Wipes clean the entire cache's keys regardless of webappPrefix.
     *
     * @return bool True on success and false on failure.
     */
    public function clearAll(): bool
    {
        $return = false;
        if ($this->enabled) {
            $return = true;
            if (! apcu_clear_cache()) {
                return false;
            }
        }
        return $return;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return array A list of key => value pairs. Cache keys that do not exist or are
     *                                                stale will have $default as value.
     */
    public function getMultiple($keys, $default = null): array
    {
        $this->checkIterable($keys);
        $return = array();
        foreach ($keys as $userKey) {
            if (! is_string($userKey)) {
                throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::iterableKeyMustBeString($userKey);
            }
            $value = $default;
            if ($this->enabled) {
                $realKey = $this->adjustKey($userKey);
                $value = $this->cacheFetch($realKey, $default);
            }
            $return[$userKey] = $value;
        }
        return $return;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable        $pairs A list of key => value pairs for multiple-set operation.
     * @param null|int|string $ttl   (optional) The TTL value of this item. If not set or set
     *                               to NULL the class default will be used.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return bool True on success and false on failure.
     */
    public function setMultiple($pairs, $ttl = null): bool
    {
        if (! $this->enabled) {
            return false;
        }
        $this->checkIterable($pairs);
        $arr = array();
        foreach ($pairs as $key => $value) {
            if (! is_string($key)) {
                throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::iterableKeyMustBeString($key);
            }
            $realKey = $this->adjustKey($key);
            $arr[$realKey] = $value;
        }
        foreach ($arr as $realKey => $value) {
            if (! $this->cacheStore($realKey, $value, $ttl)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys): bool
    {
        if (! $this->enabled) {
            return false;
        }
        $return = true;
        $this->checkIterable($keys);
        foreach ($keys as $userKey) {
            if (! is_string($userKey)) {
                throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::iterableKeyMustBeString($userKey);
            }
            $realKey = $this->adjustKey($userKey);
            if (! $this->cacheDelete($realKey)) {
                $return = false;
            }
        }
        return $return;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     */
    public function has($key): bool
    {
        $key = $this->adjustKey($key);
        if ($this->enabled) {
            return apcu_exists($key);
        }
        return false;
    }

    /**
     * Returns the actual internal key being used with APCu. Needed for unit testing.
     *
     * @param string $key The key you wanted translated to the APCu key.
     *
     * @throws \AWonderPHP\SimpleCacheAPCu\StrictTypeException
     * @throws \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException
     *
     * @return string
     */
    public function getRealKey($key): string
    {
        return $this->adjustKey($key);
    }

    /**
     * Class constructor function. Takes three arguments with defaults.
     *
     * @param string $webappPrefix (optional) Sets the prefix to use for internal APCu key assignment.
     *                             Useful to avoid key collisions between web applications (think of
     *                             it like a namespace). String between 3 and 32 characters in length
     *                             containing only letters A-Z (NOT case sensitive) and numbers 0-9.
     *                             Defaults to "Default".
     * @param string $salt         (optional) A salt to use in the generation of the hash used as the
     *                             internal APCu key. Must be at least eight characters long. There is
     *                             a default salt that is used if you do not specify. Note that when
     *                             you change the salt, all the internal keys change.
     * @param bool   $strictType   (optional) When set to true, type is strictly enforced. When set to
     *                             false (the default) an attempt is made to cast to the expected type.
     */
    public function __construct($webappPrefix = null, $salt = null, bool $strictType = false)
    {
        if (extension_loaded('apcu') && ini_get('apc.enabled')) {
            $invalidTypes = array('array', 'object', 'boolean');
            if (! is_null($webappPrefix)) {
                if (! $strictType) {
                    $type = gettype($webappPrefix);
                    if (! in_array($type, $invalidTypes)) {
                        $webappPrefix = (string)$webappPrefix;
                    }
                }
                $this->setWebAppPrefix($webappPrefix);
            }
            if (! is_null($salt)) {
                if (! $strictType) {
                    $type = gettype($salt);
                    if (! in_array($type, $invalidTypes)) {
                        $salt = (string)$salt;
                    }
                }
                $this->setHashSalt($salt);
            }
            $this->enabled = true;
        }
        $this->strictType = $strictType;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>