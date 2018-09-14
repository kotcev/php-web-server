<?php

require "vendor/autoload.php";

try {
    $server = new \Kotcev\Server\Server("127.0.0.1", 1234);
} catch (Exception $e) {
    die($e->getMessage());
}

$handlerCollection = new \Kotcev\Server\HandlerCollection();

$handlerCollection->registerHandler('/', function() {
    return 'home';
});

$handlerCollection->registerHandler('/about-us', function() {
    return 'about-us';
});

$handlerCollection->registerHandler('/contacts', function() {
    return 'contacts';
});

$server->setHandlerCollection($handlerCollection);
$server->listenAndServe();