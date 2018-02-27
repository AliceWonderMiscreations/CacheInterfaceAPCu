<?php
declare(strict_types = 1);

/*
 +-------------------------------------------------------+
 |                                                       |
 | Copyright (c) 2018 Alice Wonder Miscreations          |
 |  May be used under terms of MIT license               |
 |                                                       |
 | This class provides the catch-able exceptions for     |
 |  invalid types used as arguments                      |
 |                                                       |
 +-------------------------------------------------------+
*/

namespace AliceWonderMiscreations\SimpleCacheAPCu;

class StrictTypeException extends \TypeError implements \Psr\SimpleCache\CacheException
{
    public static function cstrTypeError( $var, string $str )
    {
        $type = gettype($var);
        return new self(sprintf('The %s argument to the SimpleCacheAPCu constructor must be a string. You supplied type %s.', $str, $type));
    }
    public static function DefaultTTL( $var )
    {
        $type = gettype($var);
        return new self(sprintf('The default cache TTL must be an integer. You supplied type %s.', $type));
    }
    public static function keyTypeError( $var )
    {
        $type = gettype($var);
        return new self(sprintf('The cache key must be a string. You supplied type %s.', $type));
    }
    public static function ttlTypeError( $var )
    {
        $type = gettype($var);
        return new self(sprintf('The cache TTL argument must be an integer or a string. You supplied type %s.', $type));
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>