<?php

// just me playing with the new class file

ob_end_flush();
// tests should not take more than fraction of second
set_time_limit(5);
header("Content-Type: text/plain");

if (file_exists('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php')) {
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/InvalidArgumentException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheInterface.php');
} else {
    require('../vendor/psr/simple-cache/src/CacheException.php');
    require('../vendor/psr/simple-cache/src/InvalidArgumentException.php');
    require('../vendor/psr/simple-cache/src/CacheInterface.php');
}

if (file_exists('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/InvalidArgumentException.php')) {
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/InvalidArgumentException.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/StrictTypeException.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/InvalidSetupException.php');
    
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/SimpleCacheAPCu.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/SimpleCacheAPCuSodium.php');
    
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuUnitTest.php');    
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuTypeErrorTest.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuInvalidArgumentTest.php');
} else {
    require('../lib/InvalidArgumentException.php');
    require('../lib/StrictTypeException.php');
    require('../lib/InvalidSetupException.php');
    
    require('../lib/SimpleCacheAPCu.php');
    require('../lib/SimpleCacheAPCuSodium.php');
    
    require('../lib/Test/SimpleCacheAPCuUnitTest.php');    
    require('../lib/Test/SimpleCacheAPCuTypeErrorTest.php');
    require('../lib/Test/SimpleCacheAPCuInvalidArgumentTest.php');
}

var_dump([
    SODIUM_LIBRARY_MAJOR_VERSION,
    SODIUM_LIBRARY_MINOR_VERSION,
    SODIUM_LIBRARY_VERSION
]);

//$key = "f42f663e72f74b9e852b172df7f57ff4ab42e505167116e13dacd0d1daf00e77";

$key = "/srv/DEFAULT.json";

$mystring = "This here be a string that I am going to try to crypto-cache";

use \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium as CryptoCache;

$fubar = new CryptoCache($key, null, null, 'garbage');

var_dump($fubar);

$fubar->set('testkey', $mystring);

$testTwo = $fubar->get('testkey');

var_dump($testTwo);

$fubar->set('another test', $mystring);

var_dump($fubar);

$aaa = SODIUM_CRYPTO_GENERICHASH_KEYBYTES_MAX;
var_dump($aaa);
































?>