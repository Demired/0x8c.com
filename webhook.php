<?php
require(__DIR__ . "/vendor/autoload.php");

use GitHubWebhook\Handler;

$handler = new Handler("123456", __DIR__,"origin master");
if($handler->handle()) {
    file_put_contents('true','ok');
} else {
    file_put_contents('false','Wrong secret');
}
