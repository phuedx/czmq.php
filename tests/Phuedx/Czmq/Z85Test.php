<?php

namespace Test\Phuedx\Czmq;

use Phuedx\Czmq\Z85;

class Z85Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function test_decode_should_throw_an_exception_when_the_length_of_the_string_isnt_divisible_by_five()
    {
        Z85::decode('Hell');
    }

    public function test_decode_should_return_an_empty_string_when_the_string_is_empty()
    {
        $this->assertEquals('', Z85::decode(''));
    }

    public function test_decode()
    {
        $this->assertEquals("\x86\x4F\xD2\x6F\xB5\x59\xF7\x5B", Z85::decode('HelloWorld'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function test_encode_should_throw_an_exception_when_the_length_of_the_string_isnt_divisible_by_four()
    {
        Z85::encode("\x86\x4F\xD2");
    }

    public function test_encode_should_return_an_empty_string_when_the_string_is_empty()
    {
        $this->assertEquals('', Z85::encode(''));
    }

    public function test_encode()
    {
        $this->assertEquals('HelloWorld', Z85::encode("\x86\x4F\xD2\x6F\xB5\x59\xF7\x5B"));
    }
}
