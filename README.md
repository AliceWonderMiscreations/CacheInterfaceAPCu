SimpleCacheAPCu
===============

This is an implementation of [PSR-16](https://www.php-fig.org/psr/psr-16/) for
the APCu caching engine.

Please refer to the files `INSTALL.md`, `USAGE.md`, and `SURVIVAL.md` for more
information specific to SimpleCacheAPCu.

Please refer to the file `LICENSE.md` for the terms of the MIT License this
software is released under.


About APCu Caching
------------------

[APCu](https://php.net/manual/en/book.apcu.php) caches `key => value` pairs in
the web server memory. The `key` must be a string but the `value` can be any
type that can be serialized.

Effective use of APCu can greatly improve the performance of your web
applications, reducing how often your web application needs to make database or
filesystem queries or process data from those queries. The information is
can usually be retrieved straight from system memory in the form your web
application needs it to be in.

APCu cache *only* lives in the web server memory. If you restart the web server
daemon, everything stored is gone. If the web server needs the memory for its
own uses, it will dump some or all of the `key => value` pairs.

When web applications need information that *can be* cached, a unique key for
that information that is stable should be created. The web application should
then attempt to fetch it from the cache. If it is there, use it. If it is not
there (what is called a ‘miss’) the web application should then fetch the
information by another means (e.g. database query with processing of the data)
and then store it in the cache using the key so it is *likely* there the next
time it is needed.

I emphasized the word *likely* because you can never be sure it actually will
be there.


About PHP-FIG and PSR-16
------------------------

PHP-FIG is the [PHP Framework Interop Group](https://www.php-fig.org/). They
exist largely to create standards that make it easier for different developers
around the world to create different projects that will work well with each
other. PHP-FIG was a driving force behind the PSR-0 and PSR-4 autoload
standards for example that make it *much much* easier to integrate PHP class
libraries written by other people into your web applications.

PSR-6 provided a Caching Interface standard but it is beyond the needs of many
web application developers. KISS - ‘Keep It Simple Silly’ applies for many of
us who do not need some of the features PSR-6 implements.

To meet the needs of those of us who do not need what PSR-6 implements,
[PSR-16](https://www.php-fig.org/psr/psr-16/) was developed and is now an
accepted standard.

When I read PSR-16, the defined interface it was not *that* different from my
own APCu caching class that I have personally been using for years. So I
decided to make my class meet the interface requirements, and this is the
result.
