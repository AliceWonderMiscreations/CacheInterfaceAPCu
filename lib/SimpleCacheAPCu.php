<?php
declare(strict_types=1);

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu.
 *
 * @package AWonderPHP/SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/SimpleCacheAPCu
 */

namespace AWonderPHP\SimpleCacheAPCu;

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu.
 *
 * This class implements the [PHP-FIG PSR-16](https://www.php-fig.org/psr/psr-16/)
 *  interface for a cache class.
 *
 * It needs PHP 7.1 or newer and obviously the [APCu PECL](https://pecl.php.net/package/APCu) extension.
 *  I am not sure of the minimum APCu version, I am using 5.1.9 myself at the moment.
 */
class SimpleCacheAPCu extends \AWonderPHP\SimpleCache\SimpleCache implements \Psr\SimpleCache\CacheInterface
{
    /**
     * A wrapper for the actual fetch from the cache.
     *
     * @param string $realKey The internal key used with APCu.
     * @param mixed  $default The value to return if a cache miss.
     *
     * @return mixed The value in the cached key => value pair, or $default if a cache miss.
     */
    protected function cacheFetch($realKey, $default)
    {
        $return = apcu_fetch($realKey, $success);
        if ($success) {
            return $return;
        }
        return $default;
    }//end cacheFetch()

    /**
     * A wrapper for the actual store of key => value pair in the cache.
     *
     * @param string                        $realKey The internal key used with APCu.
     * @param mixed                         $value   The value to be stored.
     * @param null|int|string|\DateInterval $ttl     The TTL value of this item.
     *
     * @return bool Returns True on success, False on failure.
     */
    protected function cacheStore($realKey, $value, $ttl): bool
    {
        $seconds = $this->ttlToSeconds($ttl);
        return apcu_store($realKey, $value, $seconds);
    }//end cacheStore()

    /**
     * A wrapper for the actual delete of a key => value pair in the cache.
     *
     * @param string $realKey The key for the key => value pair to be removed from the cache.
     *
     * @return bool Returns True on success, False on failure.
     */
    protected function cacheDelete($realKey): bool
    {
        return apcu_delete($realKey);
    }//end cacheDelete()

    /**
     * Wipes clean the entire cache's keys. This implementation only wipes for matching
     * webappPrefix (custom NON PSR-16 feature set during constructor).
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
    }//end clear()

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
    }//end clearAll()

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
    }//end has()

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
    }//end __construct()
}//end class

?>