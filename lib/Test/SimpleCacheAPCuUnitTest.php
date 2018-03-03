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
 | Purpose: Unit Testing and PSR-16 spec testing         |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu\Test;

class SimpleCacheAPCuUnitTest
{
    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testCacheMissReturnsNull($hexkey = null): bool
    {
        apcu_clear_cache();
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $a = $simpleCache->get("testKey");
        if (is_null($a)) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetString($hexkey = null): bool
    {
        $testString = "Test String";
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set("testKey", $testString);
        $a = $simpleCache->get("testKey");
        if ($a === $testString) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetInteger($hexkey = null): bool
    {
        $testInt = 5;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set("testKey", $testInt);
        $a = $simpleCache->get("testKey");
        if ($a === $testInt) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetFloats($hexkey = null): bool
    {
        $testFloat = 7.234;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set("testKey", $testFloat);
        $a = $simpleCache->get("testKey");
        if ($a === $testFloat) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetBoolean($hexkey = null): bool
    {
        $pass = 0;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set("testKey", true);
        $a = $simpleCache->get("testKey");
        if (is_bool($a)) {
            if ($a) {
                $pass++;
            }
        }
        $simpleCache->set("testKey", false);
        $a = $simpleCache->get("testKey");
        if (is_bool($a)) {
            if (! $a) {
                $pass++;
            }
        }
        if ($pass === 2) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetNull($hexkey = null): bool
    {
        $pass = 0;
        $key = "NullTestKey";
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, null);
        $a = $simpleCache->get($key);
        if (is_null($a)) {
            $pass++;
        }
        if ($simpleCache->has($key)) {
            $pass++;
        }
        if (! $simpleCache->has('kdyjifrsderyuioojhde00')) {
            $pass++;
        }
        if ($pass === 3) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetArray($hexkey = null): bool
    {
        $obj = new \stdClass;
        $obj->animal = "Frog";
        $obj->mineral = "Quartz";
        $obj->vegetable = "Spinach";
        $arr = array("testInt" => 5, "testFloat" => 3.278, "testString" => "WooHoo", "testBoolean" => true, "testNull" => null, "testArray" => array(1, 2, 3, 4, 5), "testObject" => $obj);
        $key = "TestArray";
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $arr);
        $a = $simpleCache->get($key);
        if (! is_array($a)) {
            return false;
        }
        if (! $arr["testInt"] === $a["testInt"]) {
            return false;
        }
        if (! $arr["testFloat"] === $a["testFloat"]) {
            return false;
        }
        if (! $arr["testString"] === $a["testString"]) {
            return false;
        }
        if (! $arr["testBoolean"] === $a["testBoolean"]) {
            return false;
        }
        if (! is_null($a["testNull"])) {
            return false;
        }
        if (! $arr["testArray"] === $a["testArray"]) {
            return false;
        }
        if (! $arr["testObject"] === $a["testObject"]) {
            return false;
        }
        return true;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetAndGetObject($hexkey = null): bool
    {
        $obj = new \stdClass;
        $obj->animal = "Frog";
        $obj->mineral = "Quartz";
        $obj->vegetable = "Spinach";
        $testObj = new \stdClass;
        $testObj->testInt = 5;
        $testObj->testFloat = 3.278;
        $testObj->testString = "WooHoo";
        $testObj->testBoolean = true;
        $testObj->testNull = null;
        $testObj->testArray = array(1,2,3,4,5);
        $testObj->testObject = $obj;

        $key = "TestObject";
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $testObj);
        $a = $simpleCache->get($key);
        if (! is_object($a)) {
            return false;
        }
        if (! $testObj->testInt === $a->testInt) {
            return false;
        }
        if (! $testObj->testFloat === $a->testFloat) {
            return false;
        }
        if (! $testObj->testString === $a->testString) {
            return false;
        }
        if (! $testObj->testBoolean === $a->testBoolean) {
            return false;
        }
        if (! is_null($a->testNull)) {
            return false;
        }
        if (! $testObj->testArray === $a->testArray) {
            return false;
        }
        if (! $testObj->testObject === $a->testObject) {
            return false;
        }
        return true;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testDeleteKey($hexkey = null): bool
    {
        // using keys we already set
        $pass = 0;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $a = "testKey";
        if ($simpleCache->has($a)) {
            $pass++;
            $simpleCache->delete($a);
            if (! $simpleCache->has($a)) {
                $pass++;
            }
        }
        if ($pass === 2) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testOneCharacterKey($hexkey = null): bool
    {
        $key = 'j';
        $value = 'foo';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if ($a === $value) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function test255CharacterKey($hexkey = null): bool
    {
        $a='AAAAABB';
        $b='BBBBBBBB';
        $key = '';

        for ($i=0; $i<=30; $i++) {
            $key .= $b;
        }

        $key .= $a;
        if (strlen($key) !== 255) {
            return false;
        }
        $value = 'foobar';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if ($a === $value) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testMultibyteCharacterKey($hexkey = null): bool
    {
        $key = 'いい知らせ';
        $value = 'חדשות טובות';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if ($a === $value) {
            return true;
        }
        return false;
    }

    // cache TTL tests

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testExplicitIntegerTTL($hexkey = null): bool
    {
        $key = 'testTTL';
        $value = 'TTL Time Test';
        $ttl = 37;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL === $ttl) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testUnixTimeStamp($hexkey = null): bool
    {
        $key = 'testTTL';
        $value = 'TTL Time Test';
        $rnd = rand(34, 99);
        $ttl = time() + $rnd;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL === $rnd) {
            return true;
        } elseif ($cacheTTL === ($rnd - 1)) {
            // rare race condition
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testDateRangeTTL($hexkey = null): bool
    {
        $key = 'TestDateRange';
        $value = 'one week';
        $ttl = '+1 week';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL === 0) {
            return false;
        }
        if ($cacheTTL <= 604800) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testDateString($hexkey = null): bool
    {
        $key = 'TestDateString';
        $value = 'Testing Expiration Date as String';
        $a = (24 * 60 * 60);
        $b = (48 * 60 * 60);
        $dateUnix = time() + $b;
        $dateString = date('Y-m-d', $dateUnix);
        
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value, $dateString);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL < $a) {
            return false;
        }
        if ($cacheTTL <= $b) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testVeryVeryLargeTTL($hexkey = null): bool
    {
        $key = 'Large Integer';
        $value = "Some Value";
        $testTime = time() - 7;
        
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->set($key, $value, $testTime);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL === $testTime) {
            return true;
        } elseif ($cacheTTL === ($testTime - 1)) {
            // rare race condition
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSettingDefaultTTL($hexkey = null): bool
    {
        $key = 'Setting Default';
        $value = 'Pie in the Sky';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey);
        }
        $simpleCache->setDefaultSeconds(300);
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        $cacheTTL = null;
        foreach ($info['cache_list'] as $cached) {
            if (strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if ($cacheTTL === 300) {
            return true;
        }
        return false;
    }

    // Webapp Prefix and Salt tests
    
    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSmallestWebappPrefix($hexkey = null): bool
    {
        $prefix = 'AAA';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $key = 'Some Key';
        $value = 'Some Value';
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $setPrefix = substr($realKey, 0, 4);
        $prefix .= '_';
        if ($prefix === $setPrefix) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testLargestWebappPrefix($hexkey = null): bool
    {
        $prefix = 'AAAAAAAABBBBBBBBCCCCCCCCDDDDDDDD';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $key = 'Some Key';
        $value = 'Some Value';
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $setPrefix = substr($realKey, 0, 33);
        $prefix .= '_';
        if ($prefix === $setPrefix) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSmallestSalt($hexkey = null): bool
    {
        $prefix = 'FFFFF';
        $salt = 'ldr##sdr';
        $key = 'Salt Test Key';
        $value = str_shuffle('NHzlGF@WuL1$%%70Sj)FQDwKK');
        
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $simpleCache->set($key, $value);

        if (is_null($hexkey)) {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } else {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix, $salt);
        }
        $test->set($key, $value);
        
        $a = $simpleCache->get($key);
        $b = $test->get($key);
        
        $realKeyA = $simpleCache->getRealKey($key);
        $realKeyB = $test->getRealKey($key);
        
        if ($realKeyA === $realKeyB) {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testReallyLargeSalt($hexkey = null): bool
    {
        $prefix = 'JJJJJGGyyJJJ';
        $presalt = 'zJpn1hit9u%%yn8hN#ODco$$3w8rp}Hv1bsnADDYrmLjeG';
        $salt = '';
        for ($i=0; $i<=200; $i++) {
            $salt .= str_shuffle($presalt);
        }
        $key = 'Salt Test Key';
        $value = str_shuffle('NHzlGF@WuL1$%%70Sj)FQDwKK');
        
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $simpleCache->set($key, $value);
        
        if (is_null($hexkey)) {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } else {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix, $salt);
        }
        $test->set($key, $value);
        
        $a = $simpleCache->get($key);
        $b = $test->get($key);
        
        $realKeyA = $simpleCache->getRealKey($key);
        $realKeyB = $test->getRealKey($key);
        
        if ($realKeyA === $realKeyB) {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        return false;
    }
    // iterable arguments
    
    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testSetMultiplePairs($hexkey = null):bool
    {
        $obj = new \stdClass;
        $obj->animal = "Frog";
        $obj->mineral = "Quartz";
        $obj->vegetable = "Spinach";
        $arr = array("testInt" => 5, "testFloat" => 3.278, "testString" => "WooHoo", "testBoolean" => true, "testNull" => null, "testArray" => array(1, 2, 3, 4, 5), "testObject" => $obj);
        $arr['Hello'] = null;
        $arr['Goodbye'] = null;
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('TESTITERABLE');
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, 'TESTITERABLE');
        }
        $simpleCache->setMultiple($arr);
        foreach (array('testInt', 'testFloat', 'testString', 'testBoolean', 'testArray', 'testObject') as $key) {
            $a = $simpleCache->get($key);
            switch ($key) {
                case "testObject":
                    if ($a->animal !== $obj->animal) {
                        return false;
                    }
                    if ($a->mineral !== $obj->mineral) {
                        return false;
                    }
                    if ($a->vegetable !== $obj->vegetable) {
                        return false;
                    }
                    break;
                default:
                    if ($arr[$key] !== $a) {
                        return false;
                    }
            }
        }
        // test the three that should be null
        foreach (array('testNull', 'Hello', 'Goodbye') as $key) {
            $a = $simpleCache->get($key);
            if (! is_null($a)) {
                echo "failed NULL value test with key " . $key . "\n";
                return false;
            }
            if (! $simpleCache->has($key)) {
                echo "failed NULL has test with key " . $key . "\n";
                return false;
            }
        }
        return true;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testGetMultiplePairs($hexkey = null):bool
    {
        // depends upon previous test
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('TESTITERABLE');
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, 'TESTITERABLE');
        }
        $arr = array();
        $arr[] = 'testBoolean';
        $arr[] = 'testFloat';
        $arr[] = 'testCacheMiss';
        $arr[] = 'testString';
        $result = $simpleCache->getMultiple($arr);
        if (! array_key_exists('testBoolean', $result)) {
            return false;
        }
        if (! array_key_exists('testFloat', $result)) {
            return false;
        }
        if (! array_key_exists('testCacheMiss', $result)) {
            return false;
        }
        if (! array_key_exists('testString', $result)) {
            return false;
        }
        // boolean
        if (! is_bool($result['testBoolean'])) {
            echo "Fetch of Boolean failed type\n";
            return false;
        }
        if (! $result['testBoolean']) {
            echo "Fetch of Boolean failed value\n";
            return false;
        }
        // float
        if (! is_float($result['testFloat'])) {
            echo "Fetch of Float failed type\n";
            return false;
        }
        if ($result['testFloat'] !== 3.278) {
            echo "Fetch of Float failed value\n";
            return false;
        }
        // string
        if (! is_string($result['testString'])) {
            echo "Fetch of String failed type\n";
            return false;
        }
        if ($result['testString'] !== "WooHoo") {
            echo "Fetch of String failed value\n";
            return false;
        }
        // cache miss
        if (is_null($result['testCacheMiss'])) {
            if (! $simpleCache->has('testCacheMiss')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testDeleteMultiple($hexkey = null): bool
    {
        $prefix = 'DELETEMANY';
        $arr = array();
        $records = rand(220, 370);
        for ($i=0; $i <= $records; $i++) {
            $key = 'KeyNumber-' . $i;
            $val = 'ValueNumber-' . $i;
            $arr[$key] = $val;
        }
        $start = count($arr);
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $simpleCache->setMultiple($arr);
        $del = array();
        $n = rand(75, 167);
        $max = $records - 5;
        for ($i=0; $i<$n; $i++) {
            $key = 'KeyNumber-' . rand(5, $max);
            if (! in_array($key, $del)) {
                $del[] = $key;
            }
        }
        $delcount = count($del);
        $expected = $start - $delcount;
        $simpleCache->deleteMultiple($del);
        $hits = 0;
        for ($i=0; $i<= $records; $i++) {
            $key = 'KeyNumber-' . $i;
            if ($simpleCache->has($key)) {
                $hits++;
            }
        }
        if ($expected === $hits) {
            return true;
        }
        return false;
    }

    // cache clearing operations
    
    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testClearLocalAppCache($hexkey = null): bool
    {
        $prefix = 'LOLIAMUNIQUE';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        // now real test
        $prefix = 'IAMUNIQUE';
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, $prefix);
        }
        $simpleCache->set('key1', 'value1');
        $simpleCache->set('key2', 'value2');
        $simpleCache->set('key3', 'value3');
        $simpleCache->set('key4', 'value4');
        $simpleCache->set('key5', 'value5');
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if (($count - $start) !== 5) {
            return false;
        }
        $simpleCache->clear();
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if ($count === $start) {
            return true;
        }
        return false;
    }

    /**
     * Error test
     *
     * @param null|string $hexkey A hex key
     *
     * @return bool
     */
    public static function testClearAllCache($hexkey = null): bool
    {
        if (is_null($hexkey)) {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('fofofo');
        } else {
            $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, 'fofofo');
        }
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        if ($start === 0) {
            return false;
        }
        if (is_null($hexkey)) {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('tststststs');
        } else {
            $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium($hexkey, 'tststststs');
        }
        $test->set('key1', 'value1');
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if ($count <= $start) {
            return false;
        }
        $test->clearAll();
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if ($count === 0) {
            return true;
        }
        return false;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>