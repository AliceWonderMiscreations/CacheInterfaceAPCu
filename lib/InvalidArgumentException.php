<?php
declare(strict_types = 1);

/*
 +-------------------------------------------------------+
 |                                                       |
 | Copyright (c) 2018 Alice Wonder Miscreations          |
 |  May be used under terms of MIT license               |
 |                                                       |
 | This class provides the catch-able exceptions for     |
 |  invalid arguments as required by PSR-16              |
 |                                                       |
 +-------------------------------------------------------+
*/

namespace AliceWonderMiscreations\SimpleCacheAPCu;

class InvalidArgumentException extends \InvalidArgumentException implements \Psr\SimpleCache\InvalidArgumentException
{
    public static function emptyKey()
    {
        return new self(sprintf('The cache key you supplied was an empty string. It must contain at least one character.'));
    }

    public static function invalidKeyCharacter( string $key )
    {
        return new self(sprintf('Cache keys may not contain any of the following characters: "  %s  " but your key "  %s  " does.', '{}()/\@:', $key));
    }

    public static function keyTooLong( string $key )
    {
        $length = strlen($key);
        return new self(sprintf('Cache keys may not be longer than 255 characters. Your key is %s characters long.', $length));
    }

    public static function negativeDefaultTTL( int $seconds )
    {
        return new self(sprintf('The default TTL can not be a negative number. You supplied %s.', $seconds));
    }
    
    public static function negativeTTL( int $seconds )
    {
        return new self(sprintf('The TTL can not be a negative number. You supplied %s.', $seconds));
    }
    
    public static function dateStringInPast( string $str )
    {
        return new self(sprintf('The cache expiration can not be in the past. You supplied %s.', $str));
    }
    
    public static function invalidTTL( string $str )
    {
        return new self(sprintf('The cache expiration must be a non-zero TTL in seconds, seconds from UNIX epoch, a DateInterval, or an expiration date string. You supplied: %s'), $str);
    }

    public static function saltTooShort( string $str )
    {
        $length = strlen($str);
        if($length === 0) {
            return new self(sprintf('The internal key salt must be at least 8 characters. You supplied an empty salt.'));
        } else {
            return new self(sprintf('The internal key salt must be at least 8 characters. You supplied a %s character salt: %s', $length, $str));
        }
    }

    public static function webappPrefixTooShort( string $str )
    {
        $length = strlen($str);
        if($length === 0) {
            return new self(sprintf('The WebApp Prefix must be at least 3 characters. You supplied an empty Prefix.'));
        } else {
            return new self(sprintf('The WebApp Prefix must be at least 3 characters. You supplied a %s character Prefix: %s', $length, $str));
        }
    }

    public static function webappPrefixTooLong( string $str )
    {
        $length = strlen($str);
        return new self(sprintf('The WebApp Prefix must not have more than 32 characters. You supplied a %s character Prefix.', $length));
    }

    public static function webappPrefixNotAlphaNumeric( string $str )
    {
        return new self(sprintf('The WebApp Prefix can only contain A-Z letters and 0-9 numbers. You supplied: %s', $str));
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>