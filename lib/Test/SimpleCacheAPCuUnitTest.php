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

namespace AliceWonderMiscreations\SimpleCacheAPCu\Test;

class SimpleCacheAPCuUnitTest
{
    public static function testCacheMissReturnsNull(): bool {
        apcu_clear_cache();
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        $a = $simpleCache->get("testKey");
        if(is_null($a)) {
            return true;
        }
        return false;
    }
    
    public static function testSetAndGetString(): bool {
        $testString = "Test String";
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testString);
        $a = $simpleCache->get("testKey");
        if($a === $testString) {
            return true;
        }
        return false;
    }
    
    public static function testSetAndGetInteger(): bool {
        $testInt = 5;
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testInt);
        $a = $simpleCache->get("testKey");
        if($a === $testInt) {
            return true;
        }
        return false;
    }
    
    public static function testSetAndGetFloats(): bool {
        $testFloat = 7.234;
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        $simpleCache->set("testKey", $testFloat);
        $a = $simpleCache->get("testKey");
        if($a === $testFloat) {
            return true;
        }
        return false;
    }
    
    public static function testSetAndGetBoolean(): bool {
        $pass = 0;
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
        
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set($key, $value);
        
        $test = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
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
        
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set($key, $value);
        
        $test = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        // now real test
        $prefix = 'IAMUNIQUE';
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu('fofofo');
        $simpleCache->set('key1', 'value1');
        $info = apcu_cache_info();
        $start = count($info['cache_list']);
        if($start === 0) {
            return false;
        }
        $test = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu('tststststs');
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
    
    // Exception Tests
    public static function testEmptyWebappPrefixException(): bool {
        $prefix = '   ';
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
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
    
    public static function testTypeErrorPrefixNotString(): bool {
        //null
        $prefix = null;
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        
        /* PHP seems to convert integer and float and boolean to string even with strict_types */
        //integer
//        $prefix = 5555;
//        $caught = false;
//        try {
//            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
//        } catch(\TypeError $e) {
//            $caught = true;
//        }
//        if(! $caught) {
//            var_dump($prefix);
//            return false;
//        }
        //float
//        $prefix = 55.55;
//        $caught = false;
//        try {
//            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
//        } catch(\TypeError $e) {
//            var_dump($prefix);
//            $caught = true;
//        }
//        if(! $caught) {
//            var_dump($prefix);
//            return false;
//        }  
        //boolean
//        $prefix = true;
//        $caught = false;
//        try {
//            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
//        } catch(\TypeError $e) {
//            $caught = true;
//        }
//        if(! $caught) {
//            var_dump($prefix);
//            return false;
//        }
        //array
        $prefix = array(1,2,3,4,5);
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        //object
        $prefix = new \stdClass;
        $prefix->foobar = "fubar";
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        
        return true;
    }
    
    public static function testEmptySalt(): bool {
        $prefix = '  aabb ';
        $salt = '                        ';
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
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
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
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
    
    public static function testTypeErrorSaltNotString(): bool {
        //null
        $prefix = 'hhhhhhhhhhhhhh';
        $salt = null;
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        //array
        $salt = array(1,2,3,4,5,6,7,8,9);
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        //object
        $salt = new \stdClass;
        $salt->foobar = "fubar";
        $caught = false;
        try {
            $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu($prefix, $salt);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($prefix);
            return false;
        }
        return true;
    }
    
    public static function testExceptionNegativeDefaultTTL(): bool {
        $ttl = -7;
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
    
    public static function testTypeErrorTTLNotInt(): bool {
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        //null
        $ttl = null;
        $caught = false;
        try {
            $simpleCache->setDefaultSeconds($ttl);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        //float
//        $ttl = 55.55;
//        $caught = false;
//        try {
//            $simpleCache->setDefaultSeconds($ttl);
//        } catch(\TypeError $e) {
//            $caught = true;
//        }
//        if(! $caught) {
//            var_dump($ttl);
//            return false;
//        }
        //boolean
//        $ttl = true;
//        $caught = false;
//        try {
//            $simpleCache->setDefaultSeconds($ttl);
//        } catch(\TypeError $e) {
//            $caught = true;
//        }
//        if(! $caught) {
//            var_dump($ttl);
//            return false;
//        }
        //array
        $ttl = array(1,3,5);
        $caught = false;
        try {
            $simpleCache->setDefaultSeconds($ttl);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        //object
        $ttl = new \stdClass;
        $ttl->foo = 7;
        $caught = false;
        try {
            $simpleCache->setDefaultSeconds($ttl);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        //make sure we can still set one to a positive integer
        // on same object
        $ttl = 700;
        $key = 'some key for default ttl type test';
        $value = 'some value';
        $simpleCache->setDefaultSeconds($ttl);
        $simpleCache->set($key, $value);
        
        $realKey = $simpleCache->getRealKey($key);
        $info = apcu_cache_info();
        foreach($info['cache_list'] as $cached) {
            if(strcmp($cached['info'], $realKey) === 0) {
                $cacheTTL = $cached['ttl'];
            }
        }
        if($cacheTTL === 700) {
            return true;
        }
        return false;
    }
    
    public static function testEmptyKey(): bool {
        $key = '    ';
        $value = 'Test Value';
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
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
    
    public static function testTypeErrorKeyNotString(): bool {
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        $value = '99 bottles of beer on the wall';
        //null
        $key = null;
        $caught = false;
        try {
            $simpleCache->set($key, $value);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($key);
            return false;
        }
        //array
        $key = array(3,4,5);
        $caught = false;
        try {
            $simpleCache->set($key, $value);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($key);
            return false;
        }
        //object
        $key = new \stdClass;
        $key->key = 'foo';
        $caught = false;
        try {
            $simpleCache->set($key, $value);
        } catch(\TypeError $e) {
            $caught = true;
        }
        if(! $caught) {
            var_dump($key);
            return false;
        }
        return true;
    }
    
    public static function testTypeErrorTTL(): bool {
        $key = 'foo';
        $value = 'bar';
        $simpleCache = new \AliceWonderMiscreations\SimpleCacheAPCu\SimpleCacheAPCu();
        // float
        $ttl = 65.83;
        $caught = false;
        try {
            $simpleCache->set($key, $value, $ttl);
        } catch(\TypeError $e) {
            $reference = "The cache TTL argument must be an integer or a string. You supplied type double.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                $caught = true;
            } else {
                var_dump($actual);
            }
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        // boolean
        $ttl = true;
        $caught = false;
        try {
            $simpleCache->set($key, $value, $ttl);
        } catch(\TypeError $e) {
            $reference = "The cache TTL argument must be an integer or a string. You supplied type boolean.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                $caught = true;
            } else {
                var_dump($actual);
            }
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        // array
        $ttl = array(3,4,5);
        $caught = false;
        try {
            $simpleCache->set($key, $value, $ttl);
        } catch(\TypeError $e) {
            $reference = "The cache TTL argument must be an integer or a string. You supplied type array.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                $caught = true;
            } else {
                var_dump($actual);
            }
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        // object
        $ttl = new \stdClass;
        $ttl->foobar = "fubar";
        $caught = false;
        try {
            $simpleCache->set($key, $value, $ttl);
        } catch(\TypeError $e) {
            $reference = "The cache TTL argument must be an integer or a string. You supplied type object.";
            $actual = $e->getMessage();
            if($reference === $actual) {
                $caught = true;
            } else {
                var_dump($actual);
            }
        }
        if(! $caught) {
            var_dump($ttl);
            return false;
        }
        return true;
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>