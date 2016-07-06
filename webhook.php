<?php
// +----------------------------------------------------------------
// | BG [ No News Is Good News ]
// +----------------------------------------------------------------
// | Copyright (c) 2015-2016 http://thinknet.cc All right reserved
// +----------------------------------------------------------------
// | Time : 2016/7/6 15:48
// +----------------------------------------------------------------
// | Author： 0x8c <zhangyuan@thinknet.cc>
// +----------------------------------------------------------------

class Handler
{
    private $secret;
    private $remote;
    private $gitDir;
    private $data;
    private $event;
    private $delivery;
    private $gitOutput;

    public function __construct($secret, $gitDir, $remote = null)
    {
        $this->secret = $secret;
        $this->remote = $remote;
        $this->gitDir = $gitDir;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDelivery()
    {
        return $this->delivery;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getGitDir()
    {
        return $this->gitDir;
    }

    public function getGitOutput()
    {
        return $this->gitOutput;
    }

    public function getRemote()
    {
        return $this->remote;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function handle()
    {
        if (!$this->validate()) {
            return false;
        }
        $c = "sudo git --work-tree={$this->gitDir} pull -f {$this->remote}";
        exec($c, $this->gitOutput);
        return true;
    }

    public function validate()
    {
        $signature = @$_SERVER['HTTP_X_HUB_SIGNATURE'];
        $event = @$_SERVER['HTTP_X_GITHUB_EVENT'];
        $delivery = @$_SERVER['HTTP_X_GITHUB_DELIVERY'];
        $payload = file_get_contents('php://input');

        if (!isset($signature, $event, $delivery)) {
            return false;
        }

        if (!$this->validateSignature($signature, $payload)) {
            return false;
        }

        $this->data = json_decode($payload,true);
        $this->event = $event;
        $this->delivery = $delivery;
        return true;
    }

    protected function validateSignature($gitHubSignatureHeader, $payload)
    {
        list ($algo, $gitHubSignature) = explode("=", $gitHubSignatureHeader);

        if ($algo !== 'sha1') {
            // see https://developer.github.com/webhooks/securing/
            return false;
        }

        $payloadHash = hash_hmac($algo, $payload, $this->secret);
        return ($payloadHash === $gitHubSignature);
    }
}


$handler = new Handler("123456", __DIR__,"origin master");



if($handler->handle()) {

    echo 'ok';
} else {
    echo 'Wrong secret';
}
$str = serialize($handler);
file_put_contents('obj',$str);



