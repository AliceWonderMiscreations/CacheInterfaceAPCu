Vimeo/Psalm Notes
=================


Unfixed Errors
--------------

There are two currently unfixed errors.

Assigned as [Issue 1](https://github.com/AliceWonderMiscreations/SimpleCacheAPCu/issues/1)

    ERROR: MoreSpecificImplementedParamType - lib/SimpleCacheAPCu.php:343:39 -
    Argument 3 of AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu::set has wrong
    type 'null|int|string', expecting 'null|int|Psr\SimpleCache\DateInterval'
    as defined by Psr\SimpleCache\CacheInterface::set
        public function set($key, $value, $ttl = null): bool
        
    ERROR: MoreSpecificImplementedParamType - lib/SimpleCacheAPCu.php:462:41 -
    Argument 2 of AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu::setMultiple has
    wrong type 'null|int|string', expecting 'null|int|Psr\SimpleCache\DateInterval'
    as defined by Psr\SimpleCache\CacheInterface::setMultiple
        public function setMultiple($pairs, $ttl = null): bool
        
First of all, I think there is a bug. The PSR-16 interface specification
actually specified `\DateInterval` and not `Psr\SimpleCache\DateInterval` which
is not defined. The `psalm` utility is confusing the namespace path.

However even if that bug did not exist, it still would be a valid complaint. I
specify `string` where the PSR-16 interface specification specifies
`\DateInterval`

This is caused by a confusion on my part. I thought the specification meant a
date string that could be parsed with `strtotime` but that is not what the spec
actually intended.

Personally I do not the concept of accepting a `\DateInterval` object as a TTL
parameter, but this is a bug I will fix, probably by adding a private function
that simply converts it to a UNIX time stamp.

This will be fixed before 1.1.0 release.


Suppressions
------------

### RedundantConditionGivenDocblockType

In many functions I suppress this warning. This warning is thrown when a the
doc-block specifies what parameter types are allowed and the method checks to
make sure the parameter is not of a different type.

Many people use type-hinting in the function declaration. I usually do not
because even with `declare(strict_types = 1);` PHP will often cast an incorrect
type to an accepted type in the type hint, and it is not always safe to do so.

So I specify the allowed types in the function doc-block (which is just a code
comment to the PHP interpreter), do NOT specify a type hint in the method, but
then evaluate the type myself in the function and either cast to correct type
if it is safe to do so or throw an exception.

Anyway this is why I suppress this warning in the doc block of several of my
functions. It is only redundant if you use type hinting so that PHP itself
either does the case or throws an exception, and I do not like to let PHP
itself handle it.

It is right for `psalm` to point it out, but it is a meaningless warning for
me, the violation is intentional.

### `lib/Tests/`

Lots of psalm suppressions there, because of intentional violations in the
unit testing. I do not want to ignore that directory with psalm, however,
because I want it to check the quality of the code in the unit testing, so
I use the doc block to disable tests that throw a warning or error.



























