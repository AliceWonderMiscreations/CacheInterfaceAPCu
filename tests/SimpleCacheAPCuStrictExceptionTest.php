<?php
declare(strict_types=1);

/**
 * Unit testing for SimpleCacheAPCu Exceptions strict mode.
 *
 * @package AWonderPHP/SimpleCacheAPCu
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/SimpleCacheAPCu
 */

use PHPUnit\Framework\TestCase;

/**
 * Test class for SimpleCache no strict no encryption.
 */
// @codingStandardsIgnoreLine
final class SimpleCacheAPCuStrictExceptionTest extends TestCase
{
    /**
     * The test object.
     *
     * @var \AWonderPHP\SimpleCache\SimpleCache
     */
    private $testStrict;

    /**
     * PHPUnit Setup, create an instance of SimpleCacheAPCu.
     *
     * @return void
     */
    public function setUp()
    {
        $this->testStrict = new \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu(null, null, true);
    }//end setUp()

    /**
     * Check to see if APCu is even possible.
     *
     * @return void
     */
    public function testCanWeEvenAccessApcuFromTestEnvironment(): void
    {
        $test = ini_get('apc.enable_cli');
        $test = (int)$test;
        $this->assertEquals(1, $test);
    }//end testCanWeEvenAccessApcuFromTestEnvironment()

    /* type error tests */

    /**
     * Feed float data when setting the default TTL. Strict only test.
     *
     * @psalm-suppress InvalidScalarArgument
     *
     * @return void
     */
    public function testDefaultTtlInvalidTypeFloat(): void
    {
        $ttl = 55.55;
        $this->expectException(\TypeError::class);
        $this->testStrict->setDefaultSeconds($ttl);
    }//end testDefaultTtlInvalidTypeFloat()

    /**
     * Use integer as key. Strict test only.
     *
     * @psalm-suppress InvalidScalarArgument
     *
     * @return void
     */
    public function testCacheKeyInvalidTypeInteger(): void
    {
        $value = '99 bottles of beer on the wall';
        $key = 67;
        $this->expectException(\TypeError::class);
        $this->testStrict->set($key, $value);
    }//end testCacheKeyInvalidTypeInteger()

    /**
     * Use float as key. Strict test only.
     *
     * @psalm-suppress InvalidScalarArgument
     *
     * @return void
     */
    public function testCacheKeyInvalidTypeFloat(): void
    {
        $value = '99 bottles of beer on the wall';
        $key = 67.99412;
        $this->expectException(\TypeError::class);
        $this->testStrict->set($key, $value);
    }//end testCacheKeyInvalidTypeFloat()

    /**
     * Use float for key pair ttl. Strict test only.
     *
     * @psalm-suppress InvalidScalarArgument
     *
     * @return void
     */
    public function testSetKeyPairTtlInvalidTypeFloat(): void
    {
        $ttl = 76.234;
        $this->expectException(\TypeError::class);
        $this->testStrict->set('foo', 'bar', $ttl);
    }//end testSetKeyPairTtlInvalidTypeFloat()
}//end class

?>