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

/* not yet unit tested */

namespace AWonderPHP\SimpleCacheAPCu;

class SimpleCacheAPCuSodium extends SimpleCacheAPCu
{
    /* The key to use */
    protected $cryptokey;
    
    /* constructor sets to true if CPU supports it */
    protected $aesgcm = false;
    /* ALWAYS gets increments before encryption */
    protected $nonce;

    /**
     * Checks to make sure sodium extension is available
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
     * @param $cryptokey the key to use
     *
     * @throws \TypeError
     *
     * @return void
     */
    protected function setCryptoKey($cryptokey): void
    {
        if (! is_string($cryptokey)) {
            throw \AWonderPHP\SimpleCacheAPCu\StrictTypeException::cryptoKeyNotString($cryptokey);
        }
        if (ctype_xdigit($cryptokey)) {
            if (function_exists('sodium_hex2bin')) {
                $cryptokey = sodium_hex2bin($cryptokey);
            } else {
                $cryptokey = hex2bin($cryptokey);
            }
        }
        //test that what is supplied works
        $string = 'ABC test 123 test xyz';
        if ($this->aesgcm) {
            $ciphertext = sodium_crypto_aead_aes256gcm_encrypt($string, $this->nonce, $this->nonce, $cryptokey);
            $test = sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $this->nonce, $this->nonce, $cryptokey);
        } else {
            $ciphertext = sodium_crypto_aead_chacha20poly1305_encrypt($string, $this->nonce, $this->nonce, $cryptokey);
            $test = sodium_crypto_aead_chacha20poly1305_decrypt($ciphertext, $this->nonce, $this->nonce, $cryptokey);
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
        } catch (\Error $e) {
            throw \AWonderPHP\SimpleCacheAPCu\InvalidArgumentException::serializeFailed($e->getMessage());
        }
        $oldnonce = $this->nonce;
        sodium_increment($this->nonce);
        if ($oldnonce === $this->nonce) {
            // This should never ever happen
            throw \AWonderPHP\SimpleCacheAPCu\InvalidSetupException::nonceIncrementError();
        }
        $obj = new \stdClass;
        $obj->nonce = $this->nonce;
        if ($this->aesgcm) {
            $obj->ciphertext = sodium_crypto_aead_aes256gcm_encrypt($serialized, $obj->nonce, $obj->nonce, $this->cryptokey);
        } else {
            $obj->ciphertext = sodium_crypto_aead_chacha20poly1305_ietf_encrypt($serialized, $obj->nonce, $obj->nonce, $this->cryptokey);
        }
        sodium_memzero($serialized);
        sodium_memzero($value);
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
                $serialized = sodium_crypto_aead_aes256gcm_decrypt($obj->ciphertext, $obj->nonce, $obj->nonce, $this->cryptokey);
            } catch (\Error $e) {
                error_log($e->getMessage());
                return $default;
            }
        } else {
            try {
                $serialized = sodium_crypto_aead_chacha20poly1305_ietf_decrypt($obj->ciphertext, $obj->nonce, $obj->nonce, $this->cryptokey);
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
        } catch (\AWonderPHP\SimpleCacheAPCu\InvalidArgumentException $e) {
            error_log($e->getMessage());
            sodium_memzero($value);
            return false;
        }
        sodium_memzero($value);
        return apcu_store($realKey, $obj, $seconds);
    }
    
    public function __debugInfo()
    {
        $result = get_object_vars($this);
        sodium_memzero($result['cryptokey']);
        unset($result['cryptokey']);
        return $result;
    }

    public function __destruct()
    {
        sodium_memzero($this->cryptokey);
    }
    
    public function __construct($cryptokey = null, $webappPrefix = null, $salt = null, $strictType = null)
    {
        $this->checkForSodium();
        // both IETF ChaCha20 and AES256GCM use 12 bytes for nonce
        $this->nonce = random_bytes(12);
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