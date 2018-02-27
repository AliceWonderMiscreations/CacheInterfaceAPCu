<?php
declare(strict_types = 1);

/*
 +--------------------------------------------------------+
 |                                                        |
 | Copyright (c) 2018 Alice Wonder Miscreations           |
 |  May be used under terms of MIT license                |
 |                                                        |
 | This file executes unit tests for SimpleCacheAPCu as   |
 |  they can not be done with PHPUnit.                    |
 |                                                        |
 | Output is markdown compatible text/plain               |
 |                                                        |
 | Testing could be improved, e.g. handling of unexpected |
 |  errors.                                               |
 |                                                        |
 +--------------------------------------------------------+
*/

ob_end_flush();
// tests should not take more than fraction of second
set_time_limit(5);
header("Content-Type: text/plain");

$continue = false;
if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    $continue = true;
}
if(! $continue) {
    die("I must have APCu loaded and enabled.");
}

if(file_exists('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php')) {
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/InvalidArgumentException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheInterface.php');
} else {
    require('../vendor/psr/simple-cache/src/CacheException.php');
    require('../vendor/psr/simple-cache/src/InvalidArgumentException.php');
    require('../vendor/psr/simple-cache/src/CacheInterface.php');
}

if(file_exists('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/InvalidArgumentException.php')) {
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/InvalidArgumentException.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/StrictTypeException.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/SimpleCacheAPCu.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuUnitTest.php');
    
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuTypeErrorTest.php');
    require('/usr/share/ccm/custom/libraries/awonderphp/simplecacheapcu/Test/SimpleCacheAPCuInvalidArgumentTest.php');
} else {
    require('../lib/InvalidArgumentException.php');
    require('../lib/SimpleCacheAPCu.php');
    require('../lib/Test/SimpleCacheAPCuUnitTest.php');
    
    require('../lib/Test/SimpleCacheAPCuTypeErrorTest.php');
    require('../lib/Test/SimpleCacheAPCuInvalidArgumentTest.php');
}

use \AliceWonderMiscreations\SimpleCacheAPCu\Test\SimpleCacheAPCuUnitTest as CacheUnitTest;

function showTestResults( string $name, bool $result) {
    global $counter;
    global $passed;
    $counter++;
    if($result) {
        $passed ++;
        print("* Test " . $name . ": *PASSED*\n");
    } else {
        print("* __FAILURE__: Test " . $name . " __FAILURE__\n");
    }
}

echo "SimpleCacheAPCuUnitTest Unit Test Results\n=========================================\n\n";

echo "__Test Date__         : " . date("Y F j \a\\t h:i:s A e") . "  \n";
echo "__Test PHP Version__  : " . PHP_VERSION . "  \n";
echo "__Test APCu Version__ :  \n";
echo "__Test Platform__     : " . PHP_OS . "  \n";

echo "\n\nImplementation Incomplete\n-------------------------\n\n";

echo "Unit Tests for Exceptions are not yet finished.\n\n";
echo "The following functions need complete rewrite and are not tested:\n\n";

echo "* `getMultiple( \$keys, \$default = null )`\n";
echo "* `setMultiple( \$pairs, \$ttl = null )`\n";
echo "* `deleteMultiple( \$keys )`\n";

echo "\n\nTesting Single Key Features\n---------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Cache Miss Returns Null ";
$a = CacheUnitTest::testCacheMissReturnsNull();
showTestResults($name, $a);

$a = false;
$name = "Set and Get String      ";
$a = CacheUnitTest::testSetAndGetString();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Integer     ";
$a = CacheUnitTest::testSetAndGetInteger();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Floats      ";
$a = CacheUnitTest::testSetAndGetFloats();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Boolean     ";
$a = CacheUnitTest::testSetAndGetBoolean();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Null        ";
$a = CacheUnitTest::testSetAndGetNull();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Array       ";
$a = CacheUnitTest::testSetAndGetArray();
showTestResults($name, $a);

$a = false;
$name = "Set and Get Object      ";
$a = CacheUnitTest::testSetAndGetObject();
showTestResults($name, $a);

$a = false;
$name = "Delete A Key            ";
$a = CacheUnitTest::testDeleteKey();
showTestResults($name, $a);

$a = false;
$name = "One Character Key       ";
$a = CacheUnitTest::testOneCharacterKey();
showTestResults($name, $a);

$a = false;
$name = "255 Character Key       ";
$a = CacheUnitTest::test255CharacterKey();
showTestResults($name, $a);

$a = false;
$name = "Multibyte Character Key ";
$a = CacheUnitTest::testMultibyteCharacterKey();
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

echo "\n\nTesting Cache TTL Features\n--------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Set TTL In Seconds                   ";
$a = CacheUnitTest::testExplicitIntegerTTL();
showTestResults($name, $a);

$a = false;
$name = "Set Expiration to Seconds from Epoch ";
$a = CacheUnitTest::testUnixTimeStamp();
showTestResults($name, $a);

$a = false;
$name = "Set Date Range from Now              ";
$a = CacheUnitTest::testDateRangeTTL();
showTestResults($name, $a);

$a = false;
$name = "Set Expiration Date as String        ";
$a = CacheUnitTest::testDateString();
showTestResults($name, $a);

$a = false;
$name = "Set Very Very Large TTL              ";
$a = CacheUnitTest::testVeryVeryLargeTTL();
showTestResults($name, $a);

$a = false;
$name = "Set Default TTL                      ";
$a = CacheUnitTest::testSettingDefaultTTL();
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

echo "\n\nTesting Webapp Prefix and Salt Features\n---------------------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Set Smallest Webapp Prefix ";
$a = CacheUnitTest::testSmallestWebappPrefix();
showTestResults($name, $a);

$a = false;
$name = "Set Largest Webapp Prefix  ";
$a = CacheUnitTest::testLargestWebappPrefix();
showTestResults($name, $a);

$a = false;
$name = "Set Smallest Salt          ";
$a = CacheUnitTest::testSmallestSalt();
showTestResults($name, $a);

$a = false;
$name = "Set Absurdly Large Salt    ";
$a = CacheUnitTest::testReallyLargeSalt();
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

echo "\n\nTesting Clear Cache Features\n----------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Clear Specific Webapp Prefix Only  ";
$a = CacheUnitTest::testClearLocalAppCache();
showTestResults($name, $a);

$a = false;
$name = "Clear All Cache                    ";
$a = CacheUnitTest::testClearAllCache();
showTestResults($name, $a);












echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

echo "\n\nTesting Exceptions\n------------------\n\n";

use \AliceWonderMiscreations\SimpleCacheAPCu\Test\SimpleCacheAPCuTypeErrorTest as TypeTests;

echo "### Type Error Tests\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Type Error Prefix Not String Exception Strict     ";
$a = TypeTests::testTypeErrorPrefixNotStringStrict();
showTestResults($name, $a);

$a = false;
$name = "Type Error Prefix Not String Exception Loose      ";
$a = TypeTests::testTypeErrorPrefixNotString();
showTestResults($name, $a);

$a = false;
$name = "Type Error Salt Not String Exception Strict       ";
$a = TypeTests::testTypeErrorSaltNotStringStrict();
showTestResults($name, $a);

$a = false;
$name = "Type Error Salt Not String Exception Loose        ";
$a = TypeTests::testTypeErrorSaltNotString();
showTestResults($name, $a);

$a = false;
$name = "Type Error Default TTL Not Int Exception Strict   ";
$a = TypeTests::testTypeErrorDefaultTTLNotIntStrict();
showTestResults($name, $a);

$a = false;
$name = "Type Error Default TTL Not Int Exception Loose    ";
$a = TypeTests::testTypeErrorDefaultTTLNotInt();
showTestResults($name, $a);

$a = false;
$name = "Type Error Key Not String Exception Strict        ";
$a = TypeTests::testTypeErrorKeyNotStringStrict();
showTestResults($name, $a);

$a = false;
$name = "Type Error Key Not String Exception Loose         ";
$a = TypeTests::testTypeErrorKeyNotString();
showTestResults($name, $a);

$a = false;
$name = "Type Error TTL Not Int or String Exception Strict ";
$a = TypeTests::testTypeErrorTTL_Strict();
showTestResults($name, $a);

$a = false;
$name = "Type Error TTL Not Int or String Exception Loose  ";
$a = TypeTests::testTypeErrorTTL();
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

use \AliceWonderMiscreations\SimpleCacheAPCu\Test\SimpleCacheAPCuInvalidArgumentTest as ArgTests;

echo "\n### Invalid Argument Tests\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Empty Webapp Prefix Exception                   ";
$a = ArgTests::testEmptyWebappPrefixException();
showTestResults($name, $a);

$a = false;
$name = "Barely Too Short Webapp Prefix Exception        ";
$a = ArgTests::testBarelyTooShortPrefixException();
showTestResults($name, $a);

$a = false;
$name = "Non AlphaNumeric Webapp Prefix Exception        ";
$a = ArgTests::testNonAlphaNumericPrefix();
showTestResults($name, $a);

$a = false;
$name = "Empty Salt Exception                            ";
$a = ArgTests::testEmptySalt();
showTestResults($name, $a);

$a = false;
$name = "Salt Barely Too Short Exception                 ";
$a = ArgTests::testSaltBarelyTooShort();
showTestResults($name, $a);

$a = false;
$name = "Negative Default TTL Exception                  ";
$a = ArgTests::testExceptionNegativeDefaultTTL();
showTestResults($name, $a);

$a = false;
$name = "Empty Key Exception                             ";
$a = ArgTests::testEmptyKey();
showTestResults($name, $a);

$a = false;
$name = "Barely Too Long Key Exception                   ";
$a = ArgTests::testBarelyTooLongKey();
showTestResults($name, $a);

$a = false;
$name = "PSR-16 Reserved Character In Key Exception      ";
$a = ArgTests::testReservedCharacterInKey();
showTestResults($name, $a);










echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";
?>