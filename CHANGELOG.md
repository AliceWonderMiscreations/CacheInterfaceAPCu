CHANGELOG
=========

MASTER (next packaged version)
------------------------------

* Removed `psalm` suppression of `MoreSpecificImplementedParamType` as PSR-16
`psr/simple-cache` has fixed their bug in 1.0.1 release.

Version 1.1.0 (2018 March 05)
-----------------------------

* Added `SimpleCacheAPCuSodium` class (extends `SimpleCacheAPCu`) to provide
support for encryption of cache via libsodium extensions to PHP.

* Added `InvalidSetupException` class (extends `\ErrorException`) to allow
verbose error messages related to libsodium that are neither type nor argument
errors

* Issue 1 - added support for
[DateInterval](https://php.net/manual/en/class.dateinterval.php) objects.


Version 1.0.0 (2018 March 01)
-----------------------------

Initial Release
