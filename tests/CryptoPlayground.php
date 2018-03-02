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

$key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

$obj = new \stdClass;

$obj->foo = 'bar';
$obj->fubar = 37;
$obj->nutcase = (2.37 * 5.3267);
$obj->screwyou = null;
$obj->cutcrap = array('7', 7, 'hello');

$ser = serialize($obj);

var_dump($ser);

echo "\n\n\n";

//var_dump($key);

$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
$ciphertext = sodium_crypto_secretbox($ser, $nonce, $key);

var_dump($ciphertext);

echo "\n\n\n";

$plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

var_dump($plaintext);

$foo = unserialize($plaintext);

var_dump($foo);

echo "\n\nTEST ONE\n\n";

$mystring = "This here be a string that I am going to try to crypto-cache";

use \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCuSodium as CryptoCache;

$fubar = new CryptoCache($key);

var_dump($fubar);

$fubar->set('testkey', $mystring);

$real = $fubar->getRealKey('testkey');

var_dump($real);

$foo = apcu_fetch($real);

var_dump($foo);

$serialized = sodium_crypto_secretbox_open($foo->ciphertext, $foo->nonce, $key);

$test = unserialize($serialized);

var_dump($test);

$testTwo = $fubar->get('testkey');

var_dump($testTwo);

































?>