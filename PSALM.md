Vimeo/Psalm Notes
=================

In the interest of developer transparency, this file is where I will detail
currently unfixed errors and warnings from the `psalm` utility as well as
warnings that are intentionally suppressed, and why they are suppressed.

Unfixed Errors
--------------

There are no unfixed errors at this time.


Suppressions
------------

### MoreSpecificImplementedParamType

This error is caused by a bug in the \Psr\SimpleCache\CacheInterface interface.
The bug is fixed in the master branch of psr/simplecache and does not impact
code operation.

This is only suppressed in SimpleCacheAPCu `set()` and `setMultiple()`.

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

### `RedundantCondition`

Similar to the above, this is suppressed because the dock block specifies the
`\DateInterval` type and the function itself where the suppression is used
verifies the parameter is an instance of `\DateInterval`. The `psalm` utility
sees that as redundant but it is necessary since I do not type hint to have
PHP take care of it for me.

### `lib/Tests/`

Lots of psalm suppressions there, because of intentional violations in the
unit testing. I do not want to ignore that directory with psalm, however,
because I want it to check the quality of the code in the unit testing, so
I use the doc block to disable tests that throw a warning or error.



























