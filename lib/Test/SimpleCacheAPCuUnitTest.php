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
    public static function testCacheMissReturnsNull(): bool {
        apcu_clear_cache();
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $a = $simpleCache->get("testKey");
        if(is_null($a)) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetString(): bool {
        $testString = "Test String";
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testString);
        $a = $simpleCache->get("testKey");
        if($a === $testString) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetInteger(): bool {
        $testInt = 5;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testInt);
        $a = $simpleCache->get("testKey");
        if($a === $testInt) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetFloats(): bool {
        $testFloat = 7.234;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testFloat);
        $a = $simpleCache->get("testKey");
        if($a === $testFloat) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetBoolean(): bool {
        $pass = 0;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", true);
        $a = $simpleCache->get("testKey");
        if(is_bool($a)) {
            if($a) {
                $pass++;
            }
        }
        $simpleCache->set("testKey", false);
        $a = $simpleCache->get("testKey");
        if(is_bool($a)) {
            if(! $a) {
                $pass++;
            }
        }
        if($pass === 2) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetNull(): bool {
        $pass = 0;
        $key = "NullTestKey";
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, null);
        $a = $simpleCache->get($key);
        if(is_null($a)) {
            $pass++;
        }
        if($simpleCache->has($key)) {
            $pass++;
        }
        if(! $simpleCache->has('kdyjifrsderyuioojhde00')) {
            $pass++;
        }
        if($pass === 3) {
            return true;
        }
        return false;
    }

    public static function testSetAndGetArray(): bool {
        $obj = new \stdClass;
        $obj->animal = "Frog";
        $obj->mineral = "Quartz";
        $obj->vegetable = "Spinach";
        $arr = array("testInt" => 5, "testFloat" => 3.278, "testString" => "WooHoo", "testBoolean" => true, "testNull" => null, "testArray" => array(1, 2, 3, 4, 5), "testObject" => $obj);
        $key = "TestArray";
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $arr);
        $a = $simpleCache->get($key);
        if(! is_array($a)) {
            return false;
        }
        if(! $arr["testInt"] === $a["testInt"]) {
            return false;
        }
        if(! $arr["testFloat"] === $a["testFloat"]) {
            return false;
        }
        if(! $arr["testString"] === $a["testString"]) {
            return false;
        }
        if(! $arr["testBoolean"] === $a["testBoolean"]) {
            return false;
        }
        if(! is_null($a["testNull"])) {
            return false;
        }
        if(! $arr["testArray"] === $a["testArray"]) {
            return false;
        }
        if(! $arr["testObject"] === $a["testObject"]) {
            return false;
        }
        return true;
    }

    public static function testSetAndGetObject(): bool {
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
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $testObj);
        $a = $simpleCache->get($key);
        if(! is_object($a)) {
            return false;
        }
        if(! $testObj->testInt === $a->testInt) {
            return false;
        }
        if(! $testObj->testFloat === $a->testFloat) {
            return false;
        }
        if(! $testObj->testString === $a->testString) {
            return false;
        }
        if(! $testObj->testBoolean === $a->testBoolean) {
            return false;
        }
        if(! is_null($a->testNull)) {
            return false;
        }
        if(! $testObj->testArray === $a->testArray) {
            return false;
        }
        if(! $testObj->testObject === $a->testObject) {
            return false;
        }
        return true;
    }

    public static function testDeleteKey(): bool {
        // using keys we already set
        $pass = 0;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $a = "testKey";
        if($simpleCache->has($a)) {
            $pass++;
            $simpleCache->delete($a);
            if(! $simpleCache->has($a)) {
                $pass++;
            }
        }
        if($pass === 2) {
            return true;
        }
        return false;
    }

    public static function testOneCharacterKey(): bool {
        $key = 'j';
        $value = 'foo';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if($a === $value) {
            return true;
        }
        return false;
    }

    public static function test255CharacterKey(): bool {
        $a='AAAAABB';
        $b='BBBBBBBB';
        $key = '';

        for($i=0; $i<=30; $i++) {
            $key .= $b;
        }

        $key .= $a;
        if(strlen($key) !== 255) {
            return false;
        }
        $value = 'foobar';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if($a === $value) {
            return true;
        }
        return false;
    }

    public static function testMultibyteCharacterKey(): bool {
        $key = 'いい知らせ';
        $value = 'חדשות טובות';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value);
        $a = $simpleCache->get($key);
        if($a === $value) {
            return true;
        }
        return false;
    }

    // cache TTL tests

    public static function testExplicitIntegerTTL(): bool {
        $key = 'testTTL';
        $value = 'TTL Time Test';
        $ttl = 37;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === $ttl) {
            return true;
        }
        return false;
    }

    public static function testUnixTimeStamp(): bool {
        $key = 'testTTL';
        $value = 'TTL Time Test';
        $rnd = rand(34, 99);
        $ttl = time() + $rnd;
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === $rnd) {
            return true;
        } elseif($cacheTTL === ($rnd - 1)) {
            // rare race condition
            return true;
        }
        return false;
    }

    public static function testDateRangeTTL(): bool {
        $key = 'TestDateRange';
        $value = 'one week';
        $ttl = '+1 week';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value, $ttl);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === 0) {
            return false;
        }
        if($cacheTTL <= 604800) {
            return true;
        }
        return false;
    }

    public static function testDateString(): bool {
        $key = 'TestDateString';
        $value = 'Testing Expiration Date as String';
        $a = (24 * 60 * 60);
        $b = (48 * 60 * 60);
        $dateUnix = time() + $b;
        $dateString = date('Y-m-d', $dateUnix);
        
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value, $dateString);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL < $a) {
            return false;
        }
        if($cacheTTL <= $b) {
            return true;
        }
        return false;
    }

    public static function testVeryVeryLargeTTL(): bool {
        $key = 'Large Integer';
        $value = "Some Value";
        $testTime = time() - 7;
        
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set($key, $value, $testTime);
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === $testTime) {
            return true;
        } elseif($cacheTTL === ($testTime - 1)) {
            // rare race condition
            return true;
        }
        return false;
    }

    public static function testSettingDefaultTTL(): bool {
        $key = 'Setting Default';
        $value = 'Pie in the Sky';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->setDefaultSeconds(300);
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === 300) {
            return true;
        }
        return false;
    }

    // Webapp Prefix and Salt tests
    public static function testSmallestWebappPrefix(): bool {
        $prefix = 'AAA';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $key = 'Some Key';
        $value = 'Some Value';
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $setPrefix = substr($realKey, 0, 4);
        $prefix .= '_';
        if($prefix === $setPrefix) {
            return true;
        }
        return false;
    }

    public static function testLargestWebappPrefix(): bool {
        $prefix = 'AAAAAAAABBBBBBBBCCCCCCCCDDDDDDDD';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $key = 'Some Key';
        $value = 'Some Value';
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $setPrefix = substr($realKey, 0, 33);
        $prefix .= '_';
        if($prefix === $setPrefix) {
            return true;
        }
        return false;
    }

    public static function testSmallestSalt(): bool {
        $prefix = 'FFFFF';
        $salt = 'ldr##sdr';
        $key = 'Salt Test Key';
        $value = str_shuffle('NHzlGF@WuL1$%%70Sj)FQDwKK');
        
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set($key, $value);
        
        $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        $test->set($key, $value);
        
        $a = $simpleCache->get($key);
        $b = $test->get($key);
        
        $realKeyA = $simpleCache->getRealKey($key);
        $realKeyB = $test->getRealKey($key);
        
        if($realKeyA === $realKeyB) {
            return false;
        }
        if($a === $b) {
            return true;
        }
        return false;
    }

    public static function testReallyLargeSalt(): bool {
        $prefix = 'JJJJJGGyyJJJ';
        $presalt = 'zJpn1hit9u%%yn8hN#ODco$$3w8rp}Hv1bsnADDYrmLjeG';
        $salt = '';
        for($i=0; $i<=200; $i++) {
            $salt .= str_shuffle($presalt);
        }
        $key = 'Salt Test Key';
        $value = str_shuffle('NHzlGF@WuL1$%%70Sj)FQDwKK');
        
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set($key, $value);
        
        $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        $test->set($key, $value);
        
        $a = $simpleCache->get($key);
        $b = $test->get($key);
        
        $realKeyA = $simpleCache->getRealKey($key);
        $realKeyB = $test->getRealKey($key);
        
        if($realKeyA === $realKeyB) {
            return false;
        }
        if($a === $b) {
            return true;
        }
        return false;
    }

    // cache clearing operations
    public static function testClearLocalAppCache(): bool {
        $prefix = 'LOLIAMUNIQUE';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        // now real test
        $prefix = 'IAMUNIQUE';
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set('key1', 'value1');
        $simpleCache->set('key2', 'value2');
        $simpleCache->set('key3', 'value3');
        $simpleCache->set('key4', 'value4');
        $simpleCache->set('key5', 'value5');
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if(($count - $start) !== 5) {
            return false;
        }
        $simpleCache->clear();
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if($count === $start) {
            return true;
        }
        return false;
    }

    public static function testClearAllCache(): bool {
        $simpleCache = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('fofofo');
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        if($start === 0) {
            return false;
        }
        $test = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu('tststststs');
        $test->set('key1', 'value1');
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if($count <= $start) {
            return false;
        }
        $test->clearAll();
        $info = apcu_cache_info();
        $count = count($info['cache_list']);
        if($count === 0) {
            return true;
        }
        return false;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>