<?php
declare(strict_types = 1);

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu
 *
 * @package AWonderPHP\SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/SimpleCacheAPCu
 */
/*
 +-------------------------------------------------------+
 |                                                       |
 | Copyright (c) 2018 Alice Wonder Miscreations          |
 |  May be used under terms of MIT license               |
 |                                                       |
 | When implementation of PSR-16 is finished I will port |
 |  coding style to PSR-2 except I will keep trailing ?> |
 |                                                       |
 +-------------------------------------------------------+
 | Purpose: PSR-16 APCu Interface                        |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu;

class InvalidSetupException extends \ErrorException implements \Psr\SimpleCache\CacheException
{
    public static function noLibSodium()
    {
        return new self(sprintf('This class requires functions from the PECL libsodium extension.'));
    }
}

?>