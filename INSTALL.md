SimpleCacheAPCu Installation
============================

You will need to have the [PECL APCu](https://pecl.php.net/package/APCu)
installed and available.

SimpleCacheAPCu is a library, it is not a stand alone application.

If you want to use it in a project that is managed by
[Composer](https://getcomposer.org/), make sure the following is within your
`composer.json` file:

    "require": {
        "awonderphp/simplecacheapcu": "^1.2"
    },

As long as your `composer.json` allows the [Packagist](https://packagist.org/)
repository, that should pull in this library when you run the command:

    composer install


Manual Installation
-------------------

For manual installation, there are two other libraries you must install where
your autoloader can find them first:

1. [`psr/simple-cache`](https://github.com/php-fig/simple-cache/tree/master/src)
2. [`AWonderPHP/SimpleCache`](https://github.com/AliceWonderMiscreations/SimpleCache/)

Both of those libraries include exception classes that also must be installed
where your autoloader can find them.

Once those two dependencies are installed, there are two class files:

1. [`SimpleCacheAPCu`](lib/SimpleCacheAPCu.php)
2. [`SimpleCacheAPCuSodium`](lib/SimpleCacheAPCuSodium.php)

The first class provides PSR-16 without encryption, the second provides PSR-16
with encryption.

Both files use the namespace `AWonderPHP\SimpleCacheAPCu`.


RPM Installation
----------------

I have started a project called
[PHP Composer Class Manager](https://github.com/AliceWonderMiscreations/php-ccm)
but it is not yet ready for deployment, and as of today (March 21 2018) it will
likely be awhile.


Class Usage
-----------

Please see the file [`USAGE.md`](USAGE.md) for class usage.


-------------------------------------------------
__EOF__