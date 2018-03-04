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
 * Throws a \TypeError exception
 *
 * This class throws an exception when the data type provided to a class function
 * argument does not match the data type the function expects.
 *
 * Extends \TypeError and inplements
 * \Psr\SimpleCache\InvalidArgumentException
 *
 * @package AWonderPHP\SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 */
class StrictTypeException extends \TypeError implements \Psr\SimpleCache\InvalidArgumentException
{
    /**
     * Exception message when constructor argument expected to be a string
     *
     * @param mixed  $var The argument passed to the constructor
     * @param string $str The name of the argument that needed to be a string
     *
     * @return \TypeError
     */
    public static function cstrTypeError($var, string $str)
    {
        $type = gettype($var);
        return new self(sprintf('The %s argument to the SimpleCacheAPCu constructor must be a string. You supplied type %s.', $str, $type));
    }

    /**
     * Exception message when the argument passed to setDefaultSeconds method
     * is not an integer
     *
     * @param mixed $var The argument that was passed to setDefaultSeconds
     *
     * @return \TypeError
     */
    public static function defaultTTL($var)
    {
        $type = gettype($var);
        return new self(sprintf('The default cache TTL must be a \DateInterval or integer. You supplied type %s.', $type));
    }

    /**
     * Exception message when the cache key parameter is not a string.
     *
     * @param mixed $var The argument that was passed as a cache key.
     *
     * @return \TypeError
     */
    public static function keyTypeError($var)
    {
        $type = gettype($var);
        return new self(sprintf('The cache key must be a string. You supplied type %s.', $type));
    }

    /**
     * Exception message when the cache TTL parameter is not an integer or a
     * string
     *
     * @param mixed $var The argument used for the cache TTL
     *
     * @return \TypeError
     */
    public static function ttlTypeError($var)
    {
        $type = gettype($var);
        return new self(sprintf('The cache TTL argument must be a \DateInterval, integer, or a string. You supplied type %s.', $type));
    }

    /**
     * Exception message when an iterable type is required but was not passed.
     *
     * @param mixed $var The argument passes to a method that requires an iterable argument
     *
     * @return \TypeError
     */
    public static function typeNotIterable($var)
    {
        $type = gettype($var);
        return new self(sprintf('Caching functions for multiple cache operations require an iterable argument. You supplied type %s.', $type));
    }

    /**
     * Exception message when a key in an iterable key => value pair is not a string
     *
     * @param mixed $var The key that is not a string
     *
     * @return \TypeError
     */
    public static function iterableKeyMustBeString($var)
    {
        $type = gettype($var);
        return new self(sprintf('The key in an iterable argument must be a string. You supplied type %s.', $type));
    }

    /**
     * Exception message when the supplied crypto key is not of the type string.
     *
     * @param mixed $var The supplied encryption key.
     *
     * @return \TypeError
     */
    public static function cryptoKeyNotString($var)
    {
        $type = gettype($var);
        return new self(sprintf('The cipher key MUST be a string. You supplied a %s.', $type));
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>