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

/* NOT YET FUNCTIONAL and may not be real world practical. */

namespace AWonderPHP\SimpleCacheAPCu;

class SimpleCacheAPCuSodium extends SimpleCacheAPCu
{
    protected $cryptokey;

    protected function setCryptoKey($cryptokey) {
        if(! function_exists('sodium_crypto_secretbox')) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetup::noLibSodium();
        }
        if(! is_string($cryptokey)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::cryptoKeyNotString($cryptokey);
        }
        //test that what is supplied works
        $string = 'ABC test 123 test xyz';
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($string, $nonce, $cryptokey);
        $test = sodium_crypto_secretbox_open($ciphertext, $nonce, $cryptokey);
        if ($string === $test) {
            $this->cryptokey = $cryptokey;
            $this->enabled = true;
            return;
        }
        return false;
    }

    protected function weakHash($key): string
    {
        // we need to generate a different hash for encrypted
        //  data so we append salt to end of key as well
        $key = $this->salt . $key . $this->salt;
        $key = hash('ripemd160', $key);
        // 16^16 should be enough of the hash to avoid collisions
        //  but 16^20 to tell encrypted from non-encrypted
        return substr($key, 19, 20);
    }
  
    protected function encryptData($value)
    {
        try {
            $serialized = serialize($value);
        } catch(\Error $e) {
            throw \AWonderPHP\SimpleCacheAPCu::serializeFailed($e->getMessage());
        }
        $obj = new \stdClass;
        $obj->nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $obj->ciphertext = sodium_crypto_secretbox($serialized, $obj->nonce, $this->cryptokey);
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
        try {
            $serialized = sodium_crypto_secretbox_open($obj->ciphertext, $obj->nonce, $this->cryptokey);
        } catch(\Error $e) {
            error_log($e->getMessage());
            return $default;
        }
        if ($serialized === false) {
            return $default;
        }
        try {
            $value = unserialize($serialized);
        } catch(\Error $e) {
            error_log($e->getMessage());
            return $default;
        }
        return $value;
    }

    protected function cacheFetch($realKey, $default)
    {
        $obj = apcu_fetch($realKey, $success);
        if ($success) {
            return $this->decryptData($obj, $default);
        }
        return $default;
    }

    protected function cacheStore($realKey, $value, $ttl): bool
    {
        $seconds = $this->ttlToSeconds($ttl);
        try {
            $obj = $this->encryptData($value);
        } catch(\AWonderPHP\SimpleCacheAPCu\InvalidArgumentException $e) {
            error_log($e->getMessage());
            return false;
        }
        return apcu_store($realKey, $obj, $seconds);
    }
    
    public function __debugInfo() {
        $result = get_object_vars($this);
        unset($result['cryptokey']);
        return $result;
    }
    
    public function __construct($cryptokey = null, $webappPrefix = null, $salt = null, bool $strictType = false)
    {
        parent::__construct($webappPrefix, $salt, $strictType);
        if ($this->enabled) {
            // will be re-enabled with valid cryptokey
            $this->enabled = false;
            $this->setCryptoKey($cryptokey);
        }
    }

}