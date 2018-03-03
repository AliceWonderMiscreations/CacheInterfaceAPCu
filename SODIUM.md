SimpleCacheAPCuSodium Class Usage
=================================

The `SimpleCacheAPCuSodium` class extends the `SimpleCacheAPCuSodium` class to
provide AEAD encryption/decryption of the `value` portion of the `key => value`
pairs cached in APCu.

If you are running PHP 7.1 you will need to install the
[PECL libsodium](https://pecl.php.net/package/libsodium) extension. If you are
running PHP 7.2, the libsodium functions are now a standard part of PHP
starting with 7.2, you do not need to do anything. Theoretically, I am still
running 7.1.

The problem I am trying to solve, APCu does not require any authentication to
query or create cached records. It also runs directly in server memory, which
means a bug in the web server or any web server modules, whether PHP related or
not, can result in a data leak or even a code injection attack by poisoning the
cached pairs your application blindly trusts. Trust can be and often is
exploited, both in the flesh and blood world and on the Internet, including
trust programs have that data they use has not been modified.

By encrypting the cached data, both of those risks are reduced. The attacker
would need to obtain the encryption key in order to decrypt any `key => value`
pairs they managed to steal from the system memory, and the attacker would have
to obtain the encryption key in order to create fraudulent values they use to
poison your cache, or the decryption would not validate on fetch and the web
cache class would treat it like a cache miss.

The problem is not completely resolved, please see the Security section of this
document.


Encryption Dirty Work
---------------------

This class was designed so that the programmer does not have to worry about
any of the dirty work. The only thing you have to do is generate a persistent
secret, and I have even made that easy.

Encryption is performing using the PHP wrapper to
[libsodium](https://doc.libsodium.org/)

Excellent document to the PHP libsodium wrapper can be found at the
[Paragon Initiative](https://paragonie.com/book/pecl-libsodium) website.

The SimpleCacheAPCu class uses one of two ciphers:

* AES-256-GCM
* IETF variant ChaCha20+Poly1305

If your server processors supports the
[AES-NI](https://en.wikipedia.org/wiki/AES_instruction_set) instructions (most
at this point in time do), AES-256-GCM will be used as it is the faster of the
two *when AES-NI is supported by the CPU*. Otherwise ChaCha20+Poly1305 is used.

Both of those ciphers use a 32-byte secret key and a 12-byte
[nonce](https://en.wikipedia.org/wiki/Cryptographic_nonce).

Both of those ciphers are very high quality ciphers and are part of the soon to
be finalized TLS 1.3 specification. They are also very fast. The class will
decide which one to use automatically based upon processor support.

With respect to the nonce, a too common mistake (e.g. the Sony Playstation 3
mistake) is reusing the same nonce for more than one encryption session.

The SimpleCacheAPCuSodium class makes sure the nonce has been incremented
before encrypting a value, and if something is seriously broken resulting in
failure to increment the nonce, an exception is thrown rather than having a
potentially dangerous encryption take place.

The only thing the system administrator needs to worry about is generating the
secret in such a way that it can be reused each time the class is instantiated.


The Constructor
---------------

The constructor is the only part of using SimpleCacheAPCuSodium that differs
from using SimpleCacheAPCu.


--------------------------------
Will write moar tomorrow, I'm zoned. Goodnight.

























