<?php

/*
 +-------------------------------------------------------+
 |                                                       |
 | Copyright (c) 2018 Alice Wonder Miscreations          |
 |  May be used under terms of MIT license               |
 |                                                       |
 | PHPUnit does not work for unit testing where APCu is  |
 |  required, so these tests return bool true on pass or |
 |  bool false on failure.                               |
 +-------------------------------------------------------+
 | Purpose: Unit Testing for Invalid Arguments           |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu\Test;

class SimpleCacheAPCuInvalidArgumentTest
{
    public static function testEmptyWebappPrefixException(): bool {
        $prefix = '   ';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\InvalidArgumentException $e) {
            $reference = "The WebApp Prefix must be at least 3 characters. You supplied an empty Prefix.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    public static function testBarelyTooShortPrefixException(): bool {
        $prefix = '  aa ';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\InvalidArgumentException $e) {
            $reference = "The WebApp Prefix must be at least 3 characters. You supplied a 2 character Prefix: AA";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    public static function testNonAlphaNumericPrefix(): bool {
        $prefix = '  aa_bb ';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\InvalidArgumentException $e) {
            $reference = "The WebApp Prefix can only contain A-Z letters and 0-9 numbers. You supplied: AA_BB";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    // salt tests

    public static function testEmptySalt(): bool {
        $prefix = '  aabb ';
        $salt = '                        ';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } catch(\InvalidArgumentException $e) {
            $reference = "The internal key salt must be at least 8 characters. You supplied an empty salt.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    public static function testSaltBarelyTooShort(): bool {
        $prefix = '  aabb ';
        $salt = '        1234567                ';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } catch(\InvalidArgumentException $e) {
            $reference = "The internal key salt must be at least 8 characters. You supplied a 7 character salt: 1234567";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    // TTL tests

    public static function testExceptionNegativeDefaultTTL(): bool {
        $ttl = -7;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        try {
            $simpleCache->setDefaultSeconds($ttl);
        } catch(\InvalidArgumentException $e) {
            $reference = "The default TTL can not be a negative number. You supplied -7.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    // key set tests

    public static function testEmptyKey(): bool {
        $key = '    ';
        $value = 'Test Value';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        try {
            $simpleCache->set($key, $value);
        } catch(\InvalidArgumentException $e) {
            $reference = "The cache key you supplied was an empty string. It must contain at least one character.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    public static function testBarelyTooLongKey(): bool {
        $a='AAAAABB';
        $b='BBBBBBBB';
        $key = 'z';

        for($i=0; $i<=30; $i++) {
            $key .= $b;
        }

        $key .= $a;
        if(strlen($key) !== 256) {
            return false;
        }
        $value = 'foobar';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        try {
            $simpleCache->set($key, $value);
        } catch(\InvalidArgumentException $e) {
            $reference = "Cache keys may not be longer than 255 characters. Your key is 256 characters long.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                return true;
            } else {
                var_dump($actual);
            }
        }
        return false;
    }

    public static function testReservedCharacterInKey(): bool {
        $reserved = array('key{key', 'key}key', 'key(key', 'key)key', 'key/key', 'key\\key',  'key@key', 'key:key');
        $value = 'value';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $reference = 'Cache keys may not contain any of the following characters: "  {}()/\@:  " but your key';
        foreach($reserved as $key) {
            $caught = false;
            try {
                $simpleCache->set($key, $value);
            } catch(\InvalidArgumentException $e) {
                $err = $e->getMessage();
                $actual = substr($err, 0, 87);
                if($reference !== $actual) {
                    var_dump($actual);
                    return false;
                }
            }
        }
        return true;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>