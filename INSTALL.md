SimpleCacheAPCu Installation
============================


SimpleCacheAPCu is a library, it is not a stand alone application.

If you want to use it in a project that is managed by
[Composer](https://getcomposer.org/), make sure the following is within your
`composer.json` file:

    "require": {
        "awonderphp/simplecacheapcu": "^1.0"
    },

As long as your `composer.json` allows the [Packagist](https://packagist.org/)
repository, that should pull in this library when you run the command:

    composer install


Manual Installation
-------------------

For manual installation, there are three class libraries you need to have where
your autoloader can find them:

1. `SimpleCacheAPCu.php` -- This is the class library.
2. `InvalidArgumentException.php` -- This is a catchable exception library,
needed when things go wrong and part of the PSR-16 compliance.
3. `StrictTypeException.php` -- This is a catchable expection library, needed
when things go wrong and part of the PSR-16 compliance.

They will need to be put where your autoloader can find them. All three classes
use the namespace `AWonderPHP\SimpleCacheAPCu`.

Additionally you will need the three interface libraries from the PHP-FIG (or
alternative edit my three class libraries to remove the `implements` parts from
the class declarations):

1. `CacheInterface.php` -- This is the PSR-16 interface definition.
2. `CacheException.php` -- This is the base interface specification for
exceptions that need to be thrown when things go bad.
3. `InvalidArgumentException.php` -- This is the interface that exception
classes in PSR-16 compliant caching classes must implement.

Those three files are available at the PHP-FIG github:
[https://github.com/php-fig/simple-cache/tree/master/src](https://github.com/php-fig/simple-cache/tree/master/src)

All three classes use the namespace `\Psr\SimpleCache`. Put them where your
autoloader can find them.


RPM Installation
----------------

I have started a project called
[PHP Composer Class Manager](https://github.com/AliceWonderMiscreations/php-ccm)
but it is not yet ready for deployment, and as of today (February 28 2018) it
will likely be awhile.


Class Usage
-----------

Please see the file `USAGE.md` for class usage.
