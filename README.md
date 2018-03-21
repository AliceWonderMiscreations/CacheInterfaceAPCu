SimpleCacheAPCu
===============

This is an implementation of [PSR-16](https://www.php-fig.org/psr/psr-16/) for
the APCu caching engine.

Two different classes are provided. The first just provides a PSR-16 compliant
interface to APCu and the second provides encryption of the cached data via
the libsodium extension.

Please refer to the files [`INSTALL.md`](INSTALL.md), [`USAGE.md`](USAGE.md),
and [`SURVIVAL.md`](SURVIVAL.md) for more information specific to
SimpleCacheAPCu.

For instructions specific to the encryption option, see the file
[`SODIUM.md`](SODIUM.md).

Please refer to the file [`LICENSE.md`](LICENSE.md) for the terms of the MIT
License this software is released under.

* [About APCu Caching](#about-apcu-caching)
* [About PHP-FIG and PSR-16](#about-php-fig-and-psr-16)
* [Coding Standard](#coding-standard)
* [About AWonderPHP](#about-awonderphp)

About APCu Caching
------------------

[APCu](https://php.net/manual/en/book.apcu.php) caches `key => value` pairs in
the web server memory. The `key` must be a string but the `value` can be any
type that can be serialized.

Effective use of APCu can greatly improve the performance of your web
applications, reducing how often your web application needs to make database or
filesystem queries or process data from those queries.

The information can usually be retrieved straight from system memory already in
the form your web application needs it to be in, radically reducing server
response times to client queries.

APCu cache *only* lives in the web server memory. If you restart the web server
daemon, everything stored is gone. If the web server needs the memory for its
own uses, it will dump some or all of the `key => value` pairs.

If you wish to have a more persistent cache than APCu provides, you should use
[SimpleCacheRedis](https://github.com/AliceWonderMiscreations/SimpleCacheRedis)
instead.

When web applications need information that *can be* cached, a unique key for
that information that should be used. The web application then attempts to
fetch the needed information from the cache.

If it is there, the web application can then immediately use it resulting in
fast response times. When the information is not there (what is called a
‘miss’) the web application then fetches the information by another means (e.g.
database query with processing of the data) and then store it in the cache so
it is highly *likely* to be there the next time it is queried from the cache.

If you need a networked cache, APCu is not the best choice for you, see the
Redis link given earlier. However for local caching on the public facing
server, it is incredibly lightweight and fast.


About PHP-FIG and PSR-16
------------------------

PHP-FIG is the [PHP Framework Interop Group](https://www.php-fig.org/). They
exist largely to create standards that make it easier for different developers
around the world to create different projects that will work well with each
other. PHP-FIG was a driving force behind the PSR-0 and PSR-4 auto-load
standards for example that make it *much much* easier to integrate PHP class
libraries written by other people into your web applications.

The PHP-FIG previously released PSR-6 as a Caching Interface standard but the
interface requirements of PSR-6 are beyond the needs of many web application
developers. KISS - ‘Keep It Simple Silly’ applies for many of us who do not
need some of the features PSR-6 requires.

To meet the needs of those of us who do not need what PSR-6 implements,
[PSR-16](https://www.php-fig.org/psr/psr-16/) was developed and is now an
accepted standard.

When I read PSR-16, the defined interface it was not *that* different from my
own APCu caching class that I have personally been using for years. So I
decided to make my class meet the interface requirements, and this is the
result.


Coding Standard
---------------

The coding standard used is primarily
[PSR-2](https://www.php-fig.org/psr/psr-2/) except with the closing `?>`
allowed, and the addition of some
[PHPDoc](https://en.wikipedia.org/wiki/PHPDoc) requirements largely but not
completely borrowed from the
[PEAR standard](http://pear.php.net/manual/en/standards.php).

The intent is switch PHPDoc standard to
[PSR-5](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md)
if it ever becomes an accepted standard.

The `phpcs` sniff rules being used: [psr2.phpcs.xml](psr2.phpcs.xml)


About AWonderPHP
----------------

I may become homeless before the end of 2018. I do not know how to survive, I
try but what I try, it always seems to fail. This just is not a society people
like me are meant to be a part of.

If I do become homeless, I fear my mental health will deteriorate at an
accelerated rate and I do not want to witness that happening to myself.

AWonderPHP is my attempt to clean up and package a lot of the PHP classes I
personally use so that something of me will be left behind.

If you wish to help, please see the [SURVIVAL.md](SURVIVAL.md) file.

Thank you for your time.


-------------------------------------------------
__EOF__