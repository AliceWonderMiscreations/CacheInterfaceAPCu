<?php
declare(strict_types = 1);

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu that uses AEAD
 * cipher to encrypt the value in the cached key => value pair.
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
 | Purpose: PSR-16 APCu Encrypted Interface              |
 +-------------------------------------------------------+
*/

/* not yet unit tested */

namespace AWonderPHP\SimpleCacheAPCu;

/**
 * An implementation of the PSR-16 SimpleCache Interface for APCu that uses AEAD
 * cipher to encrypt the value in the cached key => value pair.
 *
 * This class implements the [PHP-FIG PSR-16](https://www.php-fig.org/psr/psr-16/)
 *  interface for a cache class.
 *
 * It needs PHP 7.1 or newer and obviously the [APCu PECL](https://pecl.php.net/package/APCu) extension.
 * In PHP 7.1 It needs the PECL libsodium (sodium) extension. The extension is built-in to PHP 7.2.
 */
class SimpleCacheAPCuSodium extends SimpleCacheAPCu
{
    /** The secret key to use
     * @var string
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected $cryptokey;

    /** constructor sets to true if CPU supports it
     * @var bool
     */
    protected $aesgcm = false;

    /**
     * ALWAYS gets increments before encryption
     * @var null|string
     */
    protected $nonce = null;

    /**
     * Checks to make sure sodium extension is available.
     *
     * Libsodium is part of PHP 7.2 but in earlier versions of PHP it needs to be installed
     * via the PECL libsodium (aka sodium) extension.
     *
     * @throws \ErrorException if no libsodium support
     *
     * @return void
     */
    protected function checkForSodium(): void
    {
        if (! function_exists('sodium_memzero')) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::noLibSodium();
        }
    }

    /**
     * Sets the cryptokey property used by the class to encrypt/decrypt
     *
     * @param string $cryptokey the key to use
     *
     * @throws \TypeError
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     *
     * @return void
     */
    protected function setCryptoKey($cryptokey): void
    {
        if (! is_string($cryptokey)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::cryptoKeyNotString($cryptokey);
        }
        if (ctype_xdigit($cryptokey)) {
            $len = strlen($cryptokey);
            $cryptokey = sodium_hex2bin($cryptokey);
        }
        // insert check here to make sure is binary integer
        if (! isset($len)) {
            $hex = sodium_bin2hex($cryptokey);
            $len = strlen($hex);
            sodium_memzero($hex);
        }
        if ($len !== 64) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::wrongByteSizeKey($len);
        }
        //test that the key supplied works
        $string = 'ABC test 123 test xyz';
        $TEST_NONCE = sodium_hex2bin('74b9e852b172df7f57ff4ab4');
        if ($this->aesgcm) {
            $ciphertext = sodium_crypto_aead_aes256gcm_encrypt($string, $TEST_NONCE, $TEST_NONCE, $cryptokey);
            $test = sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $TEST_NONCE, $TEST_NONCE, $cryptokey);
        } else {
            $ciphertext = sodium_crypto_aead_chacha20poly1305_encrypt($string, $TEST_NONCE, $TEST_NONCE, $cryptokey);
            $test = sodium_crypto_aead_chacha20poly1305_decrypt($ciphertext, $TEST_NONCE, $TEST_NONCE, $cryptokey);
        }
        if ($string === $test) {
            $this->cryptokey = $cryptokey;
            $this->enabled = true;
            sodium_memzero($cryptokey);
            return;
        }
        return;
    }

    /**
     * Reads a JSON configuration and extracts info
     *
     * @param string $file The path on the filesystem to the configuration file
     *
     * @throws \\ErrorException
     *
     * @return \stdClass object with the extracted configuration info
     */
    protected function readConfigurationFile($file)
    {
        if (! file_exists($file)) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::confNotFound($file);
        }
        if (! $json = file_get_contents($file)) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::confNotReadable($file);
        }
        if (! $config = json_decode($json)) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::confNotJson($file);
        }
        sodium_memzero($json);
        return $config;
    }

    /**
     * Creates hash substring to use in internal cache key.
     *
     * This class obfuscates the user supplied cache keys by using a substring
     * of the hex representation of a hash of that key. This function creates
     * the hex representation of the hash and grabs a 20 character substring.
     *
     * The hashing algorithm differs from the parent class so that if the same
     * salt is used for both, the hash will be completely different.
     *
     * The substr() size also differs to make it easy to see which values are
     * encrypted when looking at a dump of the stored APCu keys.
     *
     * @param string $key The user defined cache key (from key => value pair)
     *                    to hash
     *
     * @return string
     */
    protected function weakHash($key): string
    {
        $key = $key . $this->salt;
        $hash = sodium_crypto_generichash($key, $this->cryptokey, 16);
        $hexhash = sodium_bin2hex($hash);
        return substr($hexhash, 6, 20);
    }

    /**
     * Serializes the value to be cached and encrypts it.
     *
     * A specification of this function is it MUST increment the nonce BEFORE encryption and
     * verify it has incremented the nonce, throwing exception if increment failed.
     *
     * @param mixed $value The value to be serialized and encrypted
     *
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     *
     * @return \stdClass object containing nonce and encrypted value
     */
    protected function encryptData($value)
    {
        try {
            $serialized = serialize($value);
        } catch (\Error $e) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::serializeFailed($e->getMessage());
        }
        $oldnonce = $this->nonce;
        if (is_null($this->nonce)) {
            // both IETF ChaCha20 and AES256GCM use 12 bytes for nonce
            $this->nonce = random_bytes(12);
        } else {
            sodium_increment($this->nonce);
        }
        if ($oldnonce === $this->nonce) {
            // This should never ever happen
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::nonceIncrementError();
        }
        $obj = new \stdClass;
        $obj->nonce = $this->nonce;
        if ($this->aesgcm) {
            $obj->ciphertext = sodium_crypto_aead_aes256gcm_encrypt(
                $serialized,
                $obj->nonce,
                $obj->nonce,
                $this->cryptokey
            );
        } else {
            $obj->ciphertext = sodium_crypto_aead_chacha20poly1305_ietf_encrypt(
                $serialized,
                $obj->nonce,
                $obj->nonce,
                $this->cryptokey
            );
        }
        sodium_memzero($serialized);
        // RESEARCH - How to zero out non-string? I don't believe recast
        //  will do it it properly
        if (is_string($value)) {
            sodium_memzero($value);
        }
        return $obj;
    }
    
    /**
     * Returns the decrypted data retried from the APCu cache
     *
     * @param object $obj     The object containing the nonce and cyphertext
     * @param mixed  $default Always return the default if there is a problem decrypting the
     *                        the cyphertext so failure acts like a cache miss
     *
     * @return mixed The decrypted data, or the default if decrypt failed
     */
    protected function decryptData($obj, $default = null)
    {
        if (! isset($obj->nonce)) {
            return $default;
        }
        if (! isset($obj->ciphertext)) {
            return $default;
        }
        if ($this->aesgcm) {
            try {
                $serialized = sodium_crypto_aead_aes256gcm_decrypt(
                    $obj->ciphertext,
                    $obj->nonce,
                    $obj->nonce,
                    $this->cryptokey
                );
            } catch (\Error $e) {
                error_log($e->getMessage());
                return $default;
            }
        } else {
            try {
                $serialized = sodium_crypto_aead_chacha20poly1305_ietf_decrypt(
                    $obj->ciphertext,
                    $obj->nonce,
                    $obj->nonce,
                    $this->cryptokey
                );
            } catch (\Error $e) {
                error_log($e->getMessage());
                return $default;
            }
        }
        if ($serialized === false) {
            return $default;
        }
        try {
            $value = unserialize($serialized);
        } catch (\Error $e) {
            error_log($e->getMessage());
            return $default;
        }
        sodium_memzero($serialized);
        return $value;
    }

    /**
     * A wrapper for the actual fetch from the cache
     *
     * @param string $realKey The internal key used with APCu
     * @param mixed  $default The value to return if a cache miss
     *
     * @return mixed The value in the cached key => value pair, or $default if a cache miss
     */
    protected function cacheFetch($realKey, $default)
    {
        $obj = apcu_fetch($realKey, $success);
        if ($success) {
            return $this->decryptData($obj, $default);
        }
        return $default;
    }

    /**
     * A wrapper for the actual store of key => value pair in the cache
     *
     * @param string          $realKey The internal key used with APCu
     * @param mixed           $value   The value to be stored
     * @param null|int|string $ttl     The TTL value of this item. If no value is sent and the
     *                                 driver supports TTL then the library may set a default
     *                                 value for it or let the driver take care of that.
     *
     * @return bool Returns True on success, False on failure
     */
    protected function cacheStore($realKey, $value, $ttl): bool
    {
        $seconds = $this->ttlToSeconds($ttl);
        try {
            $obj = $this->encryptData($value);
        } catch (\AWonderPHP\SimpleCacheAPCu\InvalidArgumentException $e) {
            error_log($e->getMessage());
            sodium_memzero($value);
            return false;
        }
        // RESEARCH - How to zero out non-string? I don't believe recast
        //  will do it it properly
        if (is_string($value)) {
            sodium_memzero($value);
        }
        return apcu_store($realKey, $obj, $seconds);
    }

    /**
     * Zeros and then removes the cryptokey from a var_dump of the object
     * also removes nonce from var_dump
     *
     * @return array The array for var_dump()
     */
    public function __debugInfo()
    {
        $result = get_object_vars($this);
        sodium_memzero($result['cryptokey']);
        unset($result['cryptokey']);
        unset($result['nonce']);
        return $result;
    }

    /**
     * Zeros the cryptokey property on class destruction
     *
     * @return void
     */
    public function __destruct()
    {
        sodium_memzero($this->cryptokey);
    }

    /**
     * The class constructor function
     *
     * @param string $cryptokey    This can be the 32-byte key used for encrypting the value,
     *                             a hex representation of that key, or a path to to a
     *                             configuration file that contains the key.
     * @param string $webappPrefix (optional) Sets the prefix to use for internal APCu key
     *                             assignment. Useful to avoid key collisions between web
     *                             applications (think of it like a namespace). String between
     *                             3 and 32 characters in length containing only letters A-Z
     *                             (NOT case sensitive) and numbers 0-9. Defaults to
     *                             "Default".
     * @param string $salt         (optional) A salt to use in the generation of the hash used
     *                             as the internal APCu key. Must be at least eight characters
     *                             long. There is a default salt that is used if you do not
     *                             specify. Note that when you change the salt, all the
     *                             internal keys change.
     * @param bool   $strictType   (optional) When set to true, type is strictly enforced.
     *                             When set to false (the default) an attempt is made to cast
     *                             to the expected type.
     *
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function __construct($cryptokey = null, $webappPrefix = null, $salt = null, $strictType = null)
    {
        $this->checkForSodium();
        if (sodium_crypto_aead_aes256gcm_is_available()) {
            $this->aesgcm = true;
        }
        if (is_string($cryptokey)) {
            $end = substr($cryptokey, -5);
            $end = strtolower($end);
            if ($end === ".json") {
                $config = $this->readConfigurationFile($cryptokey);
                sodium_memzero($cryptokey);
            }
        }
        // use config file but override when argument passed to constructor not null
        if (! isset($config)) {
            $config = new \stdClass;
        }
        if (isset($config->hexkey)) {
            $cryptokey = $config->hexkey;
            sodium_memzero($config->hexkey);
        } // else setCryptoKey should fail, will have to test...
        if (is_null($webappPrefix)) {
            if (isset($config->prefix)) {
                $webappPrefix = $config->prefix;
            }
        }
        if (is_null($salt)) {
            if (isset($config->salt)) {
                $salt = $config->salt;
            }
        }
        if (! is_null($strictType)) {
            if (! is_bool($strictType)) {
                $strictType = true;
            }
        }
        if (is_null($strictType)) {
            if (isset($config->strict)) {
                if (is_bool($config->strict)) {
                    $strictType = $config->strict;
                }
            }
        }
        if (is_null($strictType)) {
            $strictType = false;
        }
        parent::__construct($webappPrefix, $salt, $strictType);
        if ($this->enabled) {
            // will be re-enabled with valid cryptokey
            $this->enabled = false;
            $this->setCryptoKey($cryptokey);
        }
        sodium_memzero($cryptokey);
    }
}