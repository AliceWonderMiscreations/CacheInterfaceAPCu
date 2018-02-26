SimpleCacheAPCuUnitTest Unit Test Results
=========================================

Test Date         : 2018 February 26 at 08:45:55 am UTC
Test PHP Version  : 7.1.14
Test APCu Version : 
Test Platform     : Linux


Implementation Incomplete
-------------------------

Unit Tests for Exceptions are not yet written.

The following functions need complete rewrite and are not tested:

* `getMultiple( array $keys, $default = null )`
* `setMultiple( array $pairs, int $ttl = null )`
* `deleteMultiple( array $keys )`


Testing Single Key Features
---------------------------

* Test Cache Miss Returns Null : *PASSED*
* Test Set And Fetch String    : *PASSED*
* Test Set And Fetch Integer   : *PASSED*
* Test Set And Fetch Floats    : *PASSED*
* Test Set And Fetch Boolean   : *PASSED*
* Test Set And Fetch Null      : *PASSED*
* Test Set And Fetch Array     : *PASSED*
* Test Set And Fetch Object    : *PASSED*
* Test Delete A Key            : *PASSED*
* Test One Character Key       : *PASSED*
* Test 255 Character Key       : *PASSED*
* Test Multibyte Character Key : *PASSED*

12 of 12 Unit Tests Passed.


Testing Cache TTL Features
--------------------------

* Test Set TTL In Seconds                   : *PASSED*
* Test Set Expiration to Seconds from Epoch : *PASSED*
* Test Set Date Range from Now              : *PASSED*
* Test Set Expiration Date as String        : *PASSED*
* Test Set Very Very Large TTL              : *PASSED*
* Test Set Default TTL                      : *PASSED*

6 of 6 Unit Tests Passed.


Testing Webapp Prefix and Salt Features
---------------------------------------

* Test Set Smallest Webapp Prefix : *PASSED*
* Test Set Largest Webapp Prefix  : *PASSED*
* Test Set Smallest Salt          : *PASSED*
* Test Set Absurdly Large Salt    : *PASSED*

4 of 4 Unit Tests Passed.
