<?php
file_put_contents('github-headers'.'-'.time().rand(100,999),serialize(file_get_contents('php://input','r')));
file_put_contents('github-payload'.'-'.time().rand(100,999),var_export($_SERVER,true));

    $secret = '123456';
    //获取http
    $headers = array();
    //Apache服务器才支持getallheaders函数
    if (!function_exists('getallheaders')) {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
    }else
    {
        $headers = getallheaders();
    }
    //github发送过来的签名
    $hubSignature = $headers['X-Hub-Signature'];
    list($algo, $hash) = explode('=', $hubSignature, 2);

    // 获取body内容
    $payload = file_get_contents('php://input');

    // 计算签名
    $payloadHash = hash_hmac($algo, $payload, $secret);

    // 判断签名是否匹配
    if ($hash === $payloadHash) {
        //调用shell
        file_put_contents('true','123');
    }else{
        file_put_contents('false','123');
    }


