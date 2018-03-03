SimpleCacheAPCuUnitTest Unit Test Results
=========================================

__Test Date__         : 2018 March 3 at 07:02:36 AM UTC  
__Test PHP Version__  : 7.1.14  
__Test APCu Version__ : 5.1.9  
__Test Platform__     : Linux  


Testing Single Key Features
---------------------------

* Test Cache Miss Returns Null : *PASSED*
* Test Set and Get String      : *PASSED*
* Test Set and Get Integer     : *PASSED*
* Test Set and Get Floats      : *PASSED*
* Test Set and Get Boolean     : *PASSED*
* Test Set and Get Null        : *PASSED*
* Test Set and Get Array       : *PASSED*
* Test Set and Get Object      : *PASSED*
* Test Delete A Key            : *PASSED*
* Test One Character Key       : *PASSED*
* Test 255 Character Key       : *PASSED*
* Test Multibyte Character Key : *PASSED*

12 of 12 Unit Tests Passed.


Testing Iterable Argument Features
----------------------------------

* Test Set Multiple In Iterable    : *PASSED*
* Test Get Multiple In Iterable    : *PASSED*
* Test Delete Multiple In Iterable : *PASSED*

3 of 3 Unit Tests Passed.


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


Testing Clear Cache Features
----------------------------

* Test Clear Specific Webapp Prefix Only : *PASSED*
* Test Clear All Cache                   : *PASSED*

2 of 2 Unit Tests Passed.


Testing Exceptions
------------------

These are exceptions thrown by the class when given bad data. Currently two types of
exceptions are thrown:

### Type Error Tests

* Test Type Error Prefix Not String Exception Strict           : *PASSED*
* Test Type Error Prefix Not String Exception Loose            : *PASSED*
* Test Type Error Salt Not String Exception Strict             : *PASSED*
* Test Type Error Salt Not String Exception Loose              : *PASSED*
* Test Type Error Default TTL Not Int Exception Strict         : *PASSED*
* Test Type Error Default TTL Not Int Exception Loose          : *PASSED*
* Test Type Error Key Not String Exception Strict              : *PASSED*
* Test Type Error Key Not String Exception Loose               : *PASSED*
* Test Type Error TTL Not Int or String Exception Strict       : *PASSED*
* Test Type Error TTL Not Int or String Exception Loose        : *PASSED*
* Test Type Error Arg Not Iterable in `setMultiple`            : *PASSED*
* Test Type Error Non String key in Iterable in `setMultiple`  : *PASSED*
* Test Type Error Arg Not Iterable in `getMultiple`            : *PASSED*
* Test Type Error Non String key in Iterable in `getMultiple`  : *PASSED*

14 of 14 Unit Tests Passed.

### Invalid Argument Tests

* Test Empty Webapp Prefix Exception                   : *PASSED*
* Test Barely Too Short Webapp Prefix Exception        : *PASSED*
* Test Non AlphaNumeric Webapp Prefix Exception        : *PASSED*
* Test Empty Salt Exception                            : *PASSED*
* Test Salt Barely Too Short Exception                 : *PASSED*
* Test Negative Default TTL Exception                  : *PASSED*
* Test Empty Key Exception                             : *PASSED*
* Test Barely Too Long Key Exception                   : *PASSED*
* Test PSR-16 Reserved Character In Key Exception      : *PASSED*
* Test Negative TTL in `set()` Exception               : *PASSED*
* Test Cache Exp. String in Past `set()` Exception     : *PASSED*
* Test Cache Date Range in Past `set()` Exception      : *PASSED*
* Test Bogus TTL String in `set()` Exception           : *PASSED*
* Test Illegal Key in Iterable Set                     : *PASSED*

14 of 14 Unit Tests Passed.


__END OF CURRENT TESTS__
========================

55 of 55 Total Unit Tests Passed.
