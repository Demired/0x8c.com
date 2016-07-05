<?php
require(__DIR__ . "/vendor/autoload.php");

use GitHubWebhook\Handler;

$handler = new Handler("123456", __DIR__,"origin master");
if($handler->handle()) {
    echo 'ok';
} else {
    echo 'Wrong secret';
}
