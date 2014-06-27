<?php

namespace Test\Phuedx\Czmq;

use Phuedx\Czmq\Cert;

class CertTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_shouldnt_have_null_keys_after_construction()
    {
        $cert = new Cert();
        $this->assertNotNull($cert->getSecretKey());
        $this->assertNotNull($cert->getPublicKey());
        $this->assertNotNull($cert->getSecretTxt());
        $this->assertNotNull($cert->getPublicTxt());
    }

    public function test_getMeta_returns_null_if_the_metadata_value_hasnt_been_set()
    {
        $cert = new Cert();
        $this->assertNull($cert->getMeta('foo'));
    }

    public function test_getMeta_returns_the_metadata_value_if_it_has_been_set()
    {
        $cert = new Cert();
        $cert->setMeta('foo', 'bar');
        $this->assertEquals('bar', $cert->getMeta('foo'));
    }

    // XXX (phuedx, 2014/06/27): Replicate the behaviour of ZMQCert#getMetaKeys,
    // which is defined in https://github.com/phuedx/php-zmq/blob/authApiExperiment/tests/041-cert-meta.phpt.
    public function test_getMetaKeys_returns_the_metadata_names_in_reverse_order()
    {
        $cert = new Cert();
        $cert->setMeta('foo', 'bar');
        $cert->setMeta('baz', 'quux');
        $this->assertEquals(array('baz', 'foo'), $cert->getMetaKeys());
    }

    public function test_equals_returns_false_when_the_cert_has_different_keys()
    {
        $cert1 = new Cert();
        $cert2 = new Cert();
        $this->assertFalse($cert1->equals($cert2));
    }

    public function test_equals_returns_true_when_the_cert_has_the_same_keys()
    {
        $cert1 = new Cert();
        $cert2 = clone $cert1;
        $this->assertTrue($cert1->equals($cert2));
    }
}
