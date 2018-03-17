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
 | Purpose: Test the Sodium variant constructor          |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu\Test;

/**
 * Sodium Specific unit tests
 */
class SimpleCacheAPCuSodiumUnitTest
{
    /**
     * Error Test
     *
     * @psalm-suppress NullArgument
     *
     * @return bool
     */
    public static function testNullKey(): bool
    {
        $secret = null;
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($secret);
        } catch (\TypeError $e) {
            $reference = "The cipher key MUST be a string. You supplied a NULL.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }

    /**
     * Error Test
     *
     * @return bool
     */
    public static function testSecretTooShortBinary(): bool
    {
        $secret = random_bytes(24);
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($secret);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 24 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Error Test
     *
     * @return bool
     */
    public static function testSecretTooShortHex(): bool
    {
        $secret = random_bytes(24);
        $hex = sodium_bin2hex($secret);
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hex);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 24 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Error Test
     *
     * @return bool
     */
    public static function testSecretTooLongBinary(): bool
    {
        $secret = random_bytes(40);
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($secret);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 40 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Error Test
     *
     * @return bool
     */
    public static function testSecretTooLongHex(): bool
    {
        $secret = random_bytes(40);
        $hex = sodium_bin2hex($secret);
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hex);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 40 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Error Test
     *
     * @return bool
     */
    public static function testBogusKey(): bool
    {
      //this is a 32 byte string, do we catch it?
        $hex = 'Crimson and clover over and over';
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hex);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key you supplied only contains printable characters.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }

    // configuration file test
    
    /**
     * Load Valid Configuration File
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testValidConfig($json): bool
    {
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        $key = 'test key';
        $value = 'test value';
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if ($a === $value) {
            return true;
        }
        return false;
    }
    
    /**
     * Load Configuration File with Bad JSON
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testBadJsonConfig($json): bool
    {
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        } catch (\ErrorException $e) {
            $reference = "The file " . $json . " did not contain valid JSON data.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Load Configuration File without a secret
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testConfigNoSecret($json): bool
    {
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        } catch (\TypeError $e) {
            $reference = "The cipher key MUST be a string. You supplied a NULL.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Load Configuration File secret too short
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testConfigSecretTooShort($json): bool
    {
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 31 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Load Configuration File secret too long
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testConfigSecretTooLong($json): bool
    {
        try {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        } catch (\InvalidArgumentException $e) {
            $reference = "The secret key must be 32 bytes. You provided a 33 byte key.";
            $actual = $e->getMessage();
            if ($reference === $actual) {
                return true;
            } else {
                print($actual . "\n");
            }
        }
        return false;
    }
    
    /**
     * Verify prefix passed to constructor takes precedence
     *
     * @param string $json The path to the JSON configuration file
     *
     * @return bool
     */
    public static function testConfigWithManualPrefix($json): bool
    {
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json);
        $real = $simpleCache->getRealKey('foo');
        $arr = explode('_', $real);
        $config_prefix = $arr[0];
        if ($config_prefix === 'AAAA') {
            $custom = 'BBBB';
        } else {
            $custom = 'AAAA';
        }
        $simpleTwo = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($json, $custom);
        $real = $simpleTwo->getRealKey('foo');
        $arr = explode('_', $real);
        $cus_prefix = $arr[0];
        if ($config_prefix === $cus_prefix) {
            return false;
        }
        return true;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>