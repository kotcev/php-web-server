<?php
require "vendor/autoload.php";

$server = new \Kotcev\Server\Server("127.0.0.1", 1234);

$server->listenAndServe();