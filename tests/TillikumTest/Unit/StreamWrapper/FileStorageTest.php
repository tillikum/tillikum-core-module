<?php

namespace TillikumTest\Unit\StreamWrapper;

class FileStorageTest extends \PHPUnit_Framework_TestCase
{
    protected $hash;

    public function setUp()
    {
        $this->hash = md5(openssl_random_pseudo_bytes(32));

        stream_wrapper_register('tillikum-file', 'Tillikum\StreamWrapper\FileStorage');
    }

    public function tearDown()
    {
        unlink("tillikum-file://{$this->hash}");

        stream_wrapper_unregister('tillikum-file');
    }

    public function testAllowDefaultOpen()
    {
        $this->assertTrue(
            is_resource(
                fopen("tillikum-file://{$this->hash}", 'wb+')
            )
        );
    }

    /**
     * @depends testAllowDefaultOpen
     */
    public function testReadWrite()
    {
        $fp = fopen("tillikum-file://{$this->hash}", 'wb+');

        $this->assertEquals(3, fwrite($fp, 'foo'));
        rewind($fp);
        $this->assertEquals('foo', fread($fp, 1024));
    }
}
