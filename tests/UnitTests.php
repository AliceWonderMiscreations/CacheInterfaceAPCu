<?php
ob_end_flush();
set_time_limit(0);
header("Content-Type: text/plain");

$continue = false;
if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    $continue = true;
}
if(! $continue) {
    die("I must have APCu loaded and enabled.");
}

// we can't unit test with phpunit because APCu no workie in phpunit
//  so we must roll our own unit test to run from within a web server
//  with APCU enabled

if(file_exists('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php')) {
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/InvalidArgumentException.php');
    require('/usr/share/ccm/stable/libraries/psr/simplecache/CacheInterface.php');
} else {
    require('../vendor/psr/simple-cache/src/CacheException.php');
    require('../vendor/psr/simple-cache/src/InvalidArgumentException.php');
    require('../vendor/psr/simple-cache/src/CacheInterface.php');
}

if(file_exists('/usr/share/ccm/custom/libraries/alicewondermiscreations/simplecacheapcu/InvalidArgumentException.php')) {
    require('/usr/share/ccm/custom/libraries/alicewondermiscreations/simplecacheapcu/InvalidArgumentException.php');
    require('/usr/share/ccm/custom/libraries/alicewondermiscreations/simplecacheapcu/SimpleCacheAPCu.php');
    require('/usr/share/ccm/custom/libraries/alicewondermiscreations/simplecacheapcu/Test/SimpleCacheAPCuUnitTest.php');
} else {
    require('../lib/InvalidArgumentException.php');
    require('../lib/SimpleCacheAPCu.php');
    require('../lib/Test/SimpleCacheAPCuUnitTest.php');
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

echo "Test Date         : " . date("Y F j \a\\t h:i:s a e") . "  \n";
echo "Test PHP Version  : " . PHP_VERSION . "  \n";
echo "Test APCu Version :  \n";
echo "Test Platform     : " . PHP_OS . "  \n";

echo "\n\nImplementation Incomplete\n-------------------------\n\n";

echo "Unit Tests for Exceptions are not yet written.\n\n";
echo "The following functions need complete rewrite and are not tested:\n\n";

echo "* `getMultiple( array \$keys, \$default = null )`\n";
echo "* `setMultiple( array \$pairs, int \$ttl = null )`\n";
echo "* `deleteMultiple( array \$keys )`\n";

echo "\n\nTesting Single Key Features\n---------------------------\n\n";

$counter = 0;
$passed = 0;

$a = false;
$name = "Cache Miss Returns Null ";
$a = CacheUnitTest::testCacheMissReturnsNull();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch String    ";
$a = CacheUnitTest::testSetAndGetString();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Integer   ";
$a = CacheUnitTest::testSetAndGetInteger();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Floats    ";
$a = CacheUnitTest::testSetAndGetFloats();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Boolean   ";
$a = CacheUnitTest::testSetAndGetBoolean();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Null      ";
$a = CacheUnitTest::testSetAndGetNull();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Array     ";
$a = CacheUnitTest::testSetAndGetArray();
showTestResults($name, $a);

$a = false;
$name = "Set And Fetch Object    ";
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
?>
