SimpleCacheAPCu
===============

This is an implementation of [PSR-16](https://www.php-fig.org/psr/psr-16/) for
the APCu caching engine.

Two different classes are provided, a standard class and an extension of that
class that allows for AEAD encryption of the cached data.

Please refer to the files [`INSTALL.md`](INSTALL.md), [`USAGE.md`](USAGE.md),
and [`SURVIVAL.md`](SURVIVAL.md) for more information specific to
SimpleCacheAPCu.

For instructions specific to the encryption option. see the file
[`SODIUM.md`](SODIUM.md).

Please refer to the file [`LICENSE.md`](LICENSE.md) for the terms of the MIT
License this software is released under.

* [About APCu Caching](#about-apcu-caching)
* [About PHP-FIG and PSR-16](#about-php-fig-and-psr-16)
* [Coding Standard](#coding-standard)
* [Vimeo/Psalm](#vimeo-psalm)
* [About Alice Wonder Miscreations and GNU/Linux](#about-alice-wonder-miscreations-and-gnu-linux)

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

When web applications need information that *can be* cached, a unique key for
that information that should be used. The web application then attempts to
fetch the needed information from the cache.

If it is there, the web application can then immediately use it resulting in
fast response times. When the information is not there (what is called a
‘miss’) the web application then fetches theinformation by another means (e.g.
database query with processing of the data) and then store it in the cache so
it is highly *likely* to be there the next time it is queried from the cache.

If you need a networked cache, APCu is not the best choice for you. However for
local caching on the public facing server, it is incredibly lightweight and
fast.


About PHP-FIG and PSR-16
------------------------

PHP-FIG is the [PHP Framework Interop Group](https://www.php-fig.org/). They
exist largely to create standards that make it easier for different developers
around the world to create different projects that will work well with each
other. PHP-FIG was a driving force behind the PSR-0 and PSR-4 autoload
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

I am not a huge fan of so-called ‘Coding Standards’ that are about style
than about security. I do not give a damn about things like four spaces
instead of two or `if()` vs `if ()` of `function( $var)` vs `function($var)`
and find such standards to be silly.

I care about standards like __NEVER__ putting script nodes in the `<body />`
and `ALWAYS` attempting to make the interface as
[accessible](https://www.w3.org/WAI/intro/accessibility.php) as possible and
__NEVER__ using third party resources that track users.

Those are kind of coding standards I like.

That being said, [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
has a utility called `phpcbf` that makes it cake to take the style I am happy
writing in and turning it into [PSR-2](https://www.php-fig.org/psr/psr-2/)
compliant code. With the exception of closing `?>` tags, that is what I am
doing for this project:

    <?xml version="1.0" encoding="UTF-8"?>
    <ruleset name="PHP_CodeSniffer">
      <description>The coding standard for SimpleCacheAPCu</description>
      <file>lib</file>
      <arg name="basepath" value="."/>
      <arg name="colors" />
      <rule ref="PSR2">
        <exclude name="PSR2.Files.ClosingTag" />
        <exclude name="PSR2.Files.EndFileNewline" />
      </rule>
    </ruleset>

However I will take patches that fix broken things in any form, because I am
not a stuck up prick and I can run the `phpcs` and `phpcbf` utilities myself,
which I already do anyway because PSR-2 is not what I do in my text editors
(vim mostly and sometimes [bluefish](http://bluefish.openoffice.nl/))

### Vimeo/Psalm

I recently discovered [Vimeo/Psalm](https://github.com/vimeo/psalm) and I must
say it is a __FANTASTIC TOOL__. Notes related to both unfixed errors from
`psalm` and any error/warning suppression will be maintained in the file
[`PSALM.md`](PSALM.md).

I *highly* recommend you use that tool in your own code.


About Alice Wonder Miscreations and GNU/Linux
---------------------------------------------

I have been using using Linux since [MKLinux DR3](http://mklinux.org/) in 1998.
That distribution no longer exists, other than in fond memories. It was a port
of Red Hat 5.2 to the Mach microkernel running on PowerPC hardware.

I quickly transitioned to LinuxPPC 1999 and then Yellow Dog Linux, before I
ultimately ditched PowerPC hardware for Red Hat 8 and then Fedora and now then
[CentOS](https://www.centos.org/) when I got sick and tired of Fedora releases
being End of Life as soon as the bugs were worked out and they started to feel
somewhat stable.

Currently I run CentOS 7, both on [Linode]() servers and on my desktop and my
laptop (a Thinkpad T410 that is begging me to let it die, but I can not afford
a replacement.)

I started and currently maintain the [LibreLAMP](https://librelamp.com/)
project to bring a modern LAMP stack to CentOS 7 but build against LibreSSL
instead of OpenSSL. It lets my servers have the stability of CentOS while
running modern Apache, PHP, MariaDB, Dovecot, etc.

I also run a [Multimedia Repository](https://media.librelamp.com/) that
provides a modern multimedia stack for CentOS 7 including FFmpeg, VLC, and a
modern GStreamer.

I am starting a new project to allow better management of PHP Libraries than
Composer offers. I am calling it the
[Composer Class Manager](https://github.com/AliceWonderMiscreations/CCM).

It will allow platform independent management of PHP classes in a more security
minded way than Composer does. Composer is great for development, but it really
sucks for deployment as it is much like static linking.

Many live web servers have vulnerable code they do not even know about because
Composer pulled it in as a dependency and the `composer.lock` file prevents it
from easily being updated.

The CCM project will make the web more secure, if I am able to actually do it.
See the `SURVIVAL.md` file for why I may not be able to.

Thank you for your time, and thank you for using the free software I so
passionately work on.

Alice Out.

