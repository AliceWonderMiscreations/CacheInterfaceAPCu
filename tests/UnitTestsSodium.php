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
if (! $continue) {
    die("I must have APCu loaded and enabled.");
}

$sodiumtest = false;
$sodiumcapable = false;

if(function_exists('sodium_bin2hex')) {
  $sodiumcapable = true;
  if(isset($_GET['sodium'])) {
    $sodiumtest = true;
  }
}



$parent = dirname( dirname(__FILE__) );

require($parent . '/vendor/psr/simple-cache/src/CacheException.php');
require($parent . '/vendor/psr/simple-cache/src/InvalidArgumentException.php');
require($parent . '/vendor/psr/simple-cache/src/CacheInterface.php');

require($parent . '/lib/InvalidArgumentException.php');
require($parent . '/lib/StrictTypeException.php');
require($parent . '/lib/InvalidSetupException.php');
    
require($parent . '/lib/SimpleCacheAPCu.php');
require($parent . '/lib/SimpleCacheAPCuSodium.php');
    
require($parent . '/lib/Test/SimpleCacheAPCuUnitTest.php');
require($parent . '/lib/Test/SimpleCacheAPCuTypeErrorTest.php');
require($parent . '/lib/Test/SimpleCacheAPCuInvalidArgumentTest.php');

require($parent . '/lib/Test/SimpleCacheAPCuSodiumUnitTest.php');

if($sodiumtest) {
  $notsosecret = random_bytes(32);
  $key = sodium_bin2hex($notsosecret);
} else {
  $key = null;
}
//$key = "f42f663e72f74b9e852b172df7f57ff4ab42e505167116e13dacd0d1daf00e77";

use \AWonderPHP\SimpleCacheAPCu\Test\SimpleCacheAPCuUnitTest as CacheUnitTest;

function showTestResults(string $name, bool $result)
{
    global $counter;
    global $passed;
    $counter++;
    if ($result) {
        $passed ++;
        print("* Test " . $name . ": *PASSED*\n");
    } else {
        print("* __FAILURE__: Test " . $name . " __FAILURE__\n");
    }
}

$TOTAL_PASSED=0;
$TOTAL_TESTS=0;

if($sodiumtest) {
  echo "SimpleCacheAPCu Sodium Unit Test Results\n========================================\n\n";
} else {
  echo "SimpleCacheAPCu Unit Test Results\n=================================\n\n";
  if($sodiumcapable) {
    echo "*For the sodium variant, add the __sodium__ get variable to PHP test uri*\n\n";
  }
}

echo "__Test Date__           : " . date("Y F j \a\\t h:i:s A e") . "  \n";
echo "__Test PHP Version__    : " . PHP_VERSION . "  \n";
echo "__Test APCu Version__   : " . phpversion('apcu') . "  \n";
if($sodiumtest) {
echo "__Test Sodium Version__ : " . phpversion('sodium') . "  \n";
}
echo "__Test Platform__       : " . PHP_OS . "  \n";

echo "\n\nTesting Single Key Features\n---------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Cache Miss Returns Null ";
$a = CacheUnitTest::testCacheMissReturnsNull($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get String      ";
$a = CacheUnitTest::testSetAndGetString($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Integer     ";
$a = CacheUnitTest::testSetAndGetInteger($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Floats      ";
$a = CacheUnitTest::testSetAndGetFloats($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Boolean     ";
$a = CacheUnitTest::testSetAndGetBoolean($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Null        ";
$a = CacheUnitTest::testSetAndGetNull($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Array       ";
$a = CacheUnitTest::testSetAndGetArray($key);
showTestResults($name, $a);

$a = false;
$name = "Set and Get Object      ";
$a = CacheUnitTest::testSetAndGetObject($key);
showTestResults($name, $a);

$a = false;
$name = "Delete A Key            ";
$a = CacheUnitTest::testDeleteKey($key);
showTestResults($name, $a);

$a = false;
$name = "One Character Key       ";
$a = CacheUnitTest::testOneCharacterKey($key);
showTestResults($name, $a);

$a = false;
$name = "255 Character Key       ";
$a = CacheUnitTest::test255CharacterKey($key);
showTestResults($name, $a);

$a = false;
$name = "Multibyte Character Key ";
$a = CacheUnitTest::testMultibyteCharacterKey($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

echo "\n\nTesting Iterable Argument Features\n----------------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Set Multiple In Iterable    ";
$a = CacheUnitTest::testSetMultiplePairs($key);
showTestResults($name, $a);

$a = false;
$name = "Get Multiple In Iterable    ";
$a = CacheUnitTest::testGetMultiplePairs($key);
showTestResults($name, $a);

$a = false;
$name = "Delete Multiple In Iterable ";
$a = CacheUnitTest::testDeleteMultiple($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

echo "\n\nTesting Cache TTL Features\n--------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Set TTL In Seconds                   ";
$a = CacheUnitTest::testExplicitIntegerTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Set Expiration to Seconds from Epoch ";
$a = CacheUnitTest::testUnixTimeStamp($key);
showTestResults($name, $a);

$a = false;
$name = "Set Date Range from Now              ";
$a = CacheUnitTest::testDateRangeTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Set Expiration Date as String        ";
$a = CacheUnitTest::testDateString($key);
showTestResults($name, $a);

$a = false;
$name = "Set Very Very Large TTL              ";
$a = CacheUnitTest::testVeryVeryLargeTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Set TTL as \DateInterval             ";
$a = CacheUnitTest::testDateIntervalTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Set Default TTL                      ";
$a = CacheUnitTest::testSettingDefaultTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Set Default TTL with \DateInterval   ";
$a = CacheUnitTest::testDateIntervalDefaultTTL($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

echo "\n\nTesting Webapp Prefix and Salt Features\n---------------------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Set Smallest Webapp Prefix ";
$a = CacheUnitTest::testSmallestWebappPrefix($key);
showTestResults($name, $a);

$a = false;
$name = "Set Largest Webapp Prefix  ";
$a = CacheUnitTest::testLargestWebappPrefix($key);
showTestResults($name, $a);

$a = false;
$name = "Set Smallest Salt          ";
$a = CacheUnitTest::testSmallestSalt($key);
showTestResults($name, $a);

$a = false;
$name = "Set Absurdly Large Salt    ";
$a = CacheUnitTest::testReallyLargeSalt($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

echo "\n\nTesting Clear Cache Features\n----------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Clear Specific Webapp Prefix Only ";
$a = CacheUnitTest::testClearLocalAppCache($key);
showTestResults($name, $a);

$a = false;
$name = "Clear All Cache                   ";
$a = CacheUnitTest::testClearAllCache($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

echo "\n\nTesting Exceptions\n------------------\n\n";

echo "These are exceptions thrown by the class when given bad data. Currently two types of\nexceptions are thrown:\n\n";

use \AWonderPHP\SimpleCacheAPCu\Test\SimpleCacheAPCuTypeErrorTest as TypeTests;

echo "### Type Error Tests\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Type Error Prefix Not String Exception Strict           ";
$a = TypeTests::testTypeErrorPrefixNotStringStrict($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Prefix Not String Exception Loose            ";
$a = TypeTests::testTypeErrorPrefixNotString($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Salt Not String Exception Strict             ";
$a = TypeTests::testTypeErrorSaltNotStringStrict($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Salt Not String Exception Loose              ";
$a = TypeTests::testTypeErrorSaltNotString($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Default TTL Not Int Exception Strict         ";
$a = TypeTests::testTypeErrorDefaultTTLNotIntStrict($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Default TTL Not Int Exception Loose          ";
$a = TypeTests::testTypeErrorDefaultTTLNotInt($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Key Not String Exception Strict              ";
$a = TypeTests::testTypeErrorKeyNotStringStrict($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Key Not String Exception Loose               ";
$a = TypeTests::testTypeErrorKeyNotString($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error TTL Not Int or String Exception Strict       ";
$a = TypeTests::testTypeErrorStrictTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error TTL Not Int or String Exception Loose        ";
$a = TypeTests::testTypeErrorTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Arg Not Iterable in `setMultiple`            ";
$a = TypeTests::testNotIterableSet($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Non String key in Iterable in `setMultiple`  ";
$a = TypeTests::testIterableSetNonStringIndex($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Arg Not Iterable in `getMultiple`            ";
$a = TypeTests::testNotIterableGet($key);
showTestResults($name, $a);

$a = false;
$name = "Type Error Non String key in Iterable in `getMultiple`  ";
$a = TypeTests::testIterableGetNonStringIndex($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

use \AWonderPHP\SimpleCacheAPCu\Test\SimpleCacheAPCuInvalidArgumentTest as ArgTests;

echo "\n### Invalid Argument Tests\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Empty Webapp Prefix Exception                   ";
$a = ArgTests::testEmptyWebappPrefixException($key);
showTestResults($name, $a);

$a = false;
$name = "Barely Too Short Webapp Prefix Exception        ";
$a = ArgTests::testBarelyTooShortPrefixException($key);
showTestResults($name, $a);

$a = false;
$name = "Non AlphaNumeric Webapp Prefix Exception        ";
$a = ArgTests::testNonAlphaNumericPrefix($key);
showTestResults($name, $a);

$a = false;
$name = "Empty Salt Exception                            ";
$a = ArgTests::testEmptySalt($key);
showTestResults($name, $a);

$a = false;
$name = "Salt Barely Too Short Exception                 ";
$a = ArgTests::testSaltBarelyTooShort($key);
showTestResults($name, $a);

$a = false;
$name = "Negative Default TTL Exception                  ";
$a = ArgTests::testExceptionNegativeDefaultTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Negative \DateInterval TTL Exception            ";
$a = ArgTests::testExceptionNegativeDateIntervalDefaultTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Empty Key Exception                             ";
$a = ArgTests::testEmptyKey($key);
showTestResults($name, $a);

$a = false;
$name = "Barely Too Long Key Exception                   ";
$a = ArgTests::testBarelyTooLongKey($key);
showTestResults($name, $a);

$a = false;
$name = "PSR-16 Reserved Character In Key Exception      ";
$a = ArgTests::testReservedCharacterInKey($key);
showTestResults($name, $a);

$a = false;
$name = "Negative TTL in `set()` Exception               ";
$a = ArgTests::testNegativeTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Cache Exp. String in Past `set()` Exception     ";
$a = ArgTests::testDateStringInPastTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Cache Date Range in Past `set()` Exception      ";
$a = ArgTests::testDateRangeInPastTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Bogus TTL String in `set()` Exception           ";
$a = ArgTests::testBogusStringinTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Cache \DateInterval in Past `set()` Exception   ";
$a = ArgTests::testExceptionNegativeDateIntervalTTL($key);
showTestResults($name, $a);

$a = false;
$name = "Illegal Key in Iterable Set                     ";
$a = ArgTests::testKeyInIterableSetNotLegal($key);
showTestResults($name, $a);

echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

$TOTAL_PASSED = $TOTAL_PASSED + $passed;
$TOTAL_TESTS = $TOTAL_TESTS + $counter;

use \AWonderPHP\SimpleCacheAPCu\Test\SimpleCacheAPCuSodiumUnitTest as SodiumTests;

if($sodiumtest) {

  

  echo "\n\nSodium Constructor Specific Tests\n---------------------------------\n\n";

  $counter = 0;
  $passed = 0;

  $a = false;
  $name = "Test Exception with Null Secret in Constructor         ";
  $a = SodiumTests::testNullKey();
  showTestResults($name, $a);

  $a = false;
  $name = "Test Exception Binary Key Too Short in Constructor     ";
  $a = SodiumTests::testSecretTooShortBinary();
  showTestResults($name, $a);

  $a = false;
  $name = "Test Exception Hex Key Too Short in Constructor        ";
  $a = SodiumTests::testSecretTooShortHex();
  showTestResults($name, $a);

  $a = false;
  $name = "Test Exception Binary Key Too Long in Constructor      ";
  $a = SodiumTests::testSecretTooLongBinary();
  showTestResults($name, $a);

  $a = false;
  $name = "Test Exception Hex Key Too Long in Constructor         ";
  $a = SodiumTests::testSecretTooLongHex();
  showTestResults($name, $a);
  
  $a = false;
  $name = "Test Exception Bogus Key in Constructor                ";
  $a = SodiumTests::testBogusKey();
  showTestResults($name, $a);
  
  $json = $parent . '/tests/valid.json';
  $a = false;
  $name = "Test Valid Config passed to the Constructor            ";
  $a = SodiumTests::testValidConfig($json);
  showTestResults($name, $a);
  
  $json = $parent . '/tests/badjson.json';
  $a = false;
  $name = "Test bad JSON passed to the Constructor                ";
  $a = SodiumTests::testBadJsonConfig($json);
  showTestResults($name, $a);
  
  $json = $parent . '/tests/nosecret.json';
  $a = false;
  $name = "Test Config w/o Secret passed to the Constructor       ";
  $a = SodiumTests::testConfigNoSecret($json);
  showTestResults($name, $a);
  
  $json = $parent . '/tests/shortkey.json';
  $a = false;
  $name = "Test Config w/ Secret too small passed the Constructor ";
  $a = SodiumTests::testConfigSecretTooShort($json);
  showTestResults($name, $a);
  
  $json = $parent . '/tests/longkey.json';
  $a = false;
  $name = "Test Config w/ Secret too long passed the Constructor  ";
  $a = SodiumTests::testConfigSecretTooLong($json);
  showTestResults($name, $a);
  
  $json = $parent . '/tests/valid.json';
  $a = false;
  $name = "Test Prefix passed to Constructor overrides config     ";
  $a = SodiumTests::testConfigWithManualPrefix($json);
  showTestResults($name, $a);

  echo "\n" . $passed . " of " . $counter . " Unit Tests Passed.\n";

  $TOTAL_PASSED = $TOTAL_PASSED + $passed;
  $TOTAL_TESTS = $TOTAL_TESTS + $counter;
}

echo "\n\n__END OF CURRENT TESTS__\n========================\n";

echo "\n" . $TOTAL_PASSED . " of " . $TOTAL_TESTS . " Total Unit Tests Passed.\n";

?>