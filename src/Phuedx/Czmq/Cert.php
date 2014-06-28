<?php

namespace Phuedx\Czmq;

class Cert
{
    protected $secretKey;
    protected $publicKey;
    protected $metadata;

    public function __construct()
    {
        $keypair = crypto_box_keypair();
        $this->secretKey = crypto_box_secretkey($keypair);
        $this->publicKey = crypto_box_publickey($keypair);
        $this->secretTxt = Z85::encode($this->secretKey);
        $this->publicTxt = Z85::encode($this->publicKey);
        $this->metadata = array();
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getSecretTxt()
    {
        return $this->secretTxt;
    }

    public function getPublicTxt()
    {
        return $this->publicTxt;
    }

    public function getMeta($name)
    {
        return isset($this->metadata[$name])
            ? $this->metadata[$name]
            : null;
    }

    public function setMeta($name, $format)
    {
        $this->metadata[$name] = $format;
    }

    public function getMetaKeys()
    {
        $result = array_keys($this->metadata);

        return array_reverse($result);
    }

    public function equals(Cert $cert)
    {
        return $this->publicTxt == $cert->publicTxt && $this->secretTxt == $cert->secretTxt;
    }

    public function apply(\ZMQSocket $socket)
    {
        $socket->setSockOpt(\ZMQ::SOCKOPT_CURVE_SECRETKEY, $this->secretKey);
        $socket->setSockOpt(\ZMQ::SOCKOPT_CURVE_PUBLICKEY, $this->publicKey);
    }
}
