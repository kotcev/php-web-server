<?php

require "vendor/autoload.php";

try {
    $server = new \Kotcev\Server\Server("127.0.0.1", 1234);
} catch (Exception $e) {
    die($e->getMessage());
}


/**
 * Describe your handlers (routes) here.
 */
$handlerCollection = new \Kotcev\Server\HandlerCollection();

$handlerCollection->registerHandler('/', function($request) {
    return print_r($request, true);
});

$handlerCollection->registerHandler('/about-us', function($request) {
    return 'about-us';
});

$handlerCollection->registerHandler('/contacts', function($request) {
    return 'contacts';
});

/**
 * Run the server.
 */
$server->setHandlerCollection($handlerCollection);
$server->listenAndServe();