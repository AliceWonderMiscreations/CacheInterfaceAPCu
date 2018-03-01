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
        $serialized = serialize($value);
        $obj = new \stdClass;
        $obj->nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $obj->ciphertext = sodium_crypto_secretbox($serialized, $obj->nonce, $this->cryptokey);
        return $obj;
    }
    
    protected function decryptData($obj)
    {
        $serialized = sodium_crypto_secretbox_open($obj->ciphertext, $obj->nonce, $this->cryptokey);
        if($serialized === false) {
            return null;
        }
        $value = unserialize($serialized);
        return $value;
    }

    protected function cacheFetch($realKey, $default)
    {
        $obj = apcu_fetch($realKey, $success);
        if ($success) {
            $return = $this->decryptData($obj);
        }
        return $default;
    }

    protected function cacheStore($realKey, $value, $ttl): bool
    {
        $seconds = $this->ttlToSeconds($ttl);
        $obj = $this->encryptData($value);
        return apcu_store($realKey, $obj, $seconds);
    }

}