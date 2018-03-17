<?php

// testing stuff
//set_time_limit(0);

ob_end_flush();
// tests should not take more than fraction of second
set_time_limit(0);
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

$secret = 'Crimson and clover over and over';

$test = sodium_bin2hex($secret);

if(ctype_print($secret)) {
  echo "\nOnly contains printable characters\n";
}

var_dump($test);

$good = 0;
$bad = 0;

echo "\n\n";
for($i=0; $i<4294967296; $i++) {
  //echo $i . "\n";
  $secret = random_bytes(32);
  if(ctype_print($secret)) {
    $bad++;
  } else {
    $good++;
  }
}

print("\n\nGood: " . $good . "\n\n");

print("Bad: " . $bad . "\n\n");

?>