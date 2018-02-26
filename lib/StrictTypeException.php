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
    public static function ttlTypeError( $var )
    {
        $type = gettype($var);
        return new self(sprintf('The cache TTL argument must be an integer or a string. You supplied type %s.', $type));
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>