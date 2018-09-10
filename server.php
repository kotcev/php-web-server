<?php

require "vendor/autoload.php";

try {
    $server = new \Kotcev\Server\Server("127.0.0.1", 1234);
} catch (Exception $e) {
    die($e->getMessage());
}

$server->listenAndServe();