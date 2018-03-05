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
 +-------------------------------------------------------+
 | Purpose: PSR-16 APCu Interface                        |
 +-------------------------------------------------------+
*/

namespace AWonderPHP\SimpleCacheAPCu;

/**
 * Throws a \ErrorException exception
 *
 * This class throws an exception when there is a configuration problem that prevents the
 * throwing method from properly operating.
 *
 * Extends \ErrorException and inplements
 * \Psr\SimpleCache\CacheException
 *
 * @package AWonderPHP\SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 */
class InvalidSetupException extends \ErrorException implements \Psr\SimpleCache\CacheException
{
    /**
     * Error message when libsodium is not available.
     *
     * @return \ErrorException
     */
    public static function noLibSodium()
    {
        return new self(sprintf('This class requires functions from the PECL libsodium extension.'));
    }

    /**
     * Error message when specified configuration file is not found.
     *
     * @param string $file The specified path to the file that can not be found.
     *
     * @return \ErrorException
     */
    public static function confNotFound(string $file)
    {
        return new self(sprintf('The specified configuration file %s could not be found.', $file));
    }

    /**
     * Error message when specified configuration file is not readable.
     *
     * @param string $file The specified path to the file that can not be read.
     *
     * @return \ErrorException
     */
    public static function confNotReadable(string $file)
    {
        return new self(sprintf('The specified configuration file %s could not be read.', $file));
    }

    /**
     * Error message when specified configuration file does not contain valid JSON.
     *
     * @param string $file The specified path to the file with broken JSON.
     *
     * @return \ErrorException
     */
    public static function confNotJson(string $file)
    {
        return new self(sprintf('The file %s did not contain valid JSON data.', $file));
    }

    /**
     * Error message when the class can not increment the nonce. This should never happen.
     *
     * @return \ErrorException
     */
    public static function nonceIncrementError()
    {
        return new self(sprintf('The class nonce failed to increment. This should not have happened, something is broken'));
    }
}

// Dear PSR-2: You can take my closing PHP tag when you can pry it from my cold dead fingers.
?>