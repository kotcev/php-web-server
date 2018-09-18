<?php

namespace Kotcev\Server;

use Exception;

class Server
{

    private $socket;

    private $host;

    private $port;

    /**
     * If this is set to false the server will
     * not run in threaded environment.
     *
     * @var bool
     */
    private $blocking = false;

    /**
     * @var HandlerCollection
     */
    private $handlerCollection;

    /**
     * @var array
     */
    private $clients = array();

    /**
     * Server constructor.
     * @param string $host
     * @param int $port
     * @param HandlerCollection $handlerCollection
     * @throws Exception
     */
    public function __construct(string $host, int $port, HandlerCollection $handlerCollection = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->handlerCollection = $handlerCollection;

        $this->socketSetup();
    }

    /**
     * Opens the socket, binds the socket.
     *
     * @throws Exception
     */
    private function socketSetup()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ( ! socket_bind($this->socket, $this->host, $this->port)) {
            throw new Exception('Error binding socket!');
        }
    }

    /**
     * The loop.
     */
    public function listenAndServe()
    {
        while ( true ) {
            socket_listen($this->socket);

            // If client connects to the server move on,
            // else skip this cycle.
            if ( ! $client = socket_accept($this->socket)) {
                socket_close($client);

                // skip the remaining of this cycle
                continue;
            }

            $requestHeaders = socket_read($client, 1024, PHP_BINARY_READ);
            $request = Request::makeFromHeaderString($requestHeaders);

            $dispatcher = new HandlerDispatcher($this->handlerCollection, $request);
            $response = $dispatcher->dispatch();

            if ( ! $this->blocking) {
                // Every new client is served by its own thread.
                array_push(
                    $this->clients,
                    new ClientThread($client, $request, $response)
                );
            } else {
                $client = new Client($client, $request, $response);
                $client->serve();
            }

        }
    }

    /**
     * @param HandlerCollection $handlerCollection
     */
    public function setHandlerCollection(HandlerCollection $handlerCollection) : void
    {
        $this->handlerCollection = $handlerCollection;
    }

    /**
     * @param bool $state
     */
    public function setBlocking(bool $state) : void
    {
        $this->blocking = $state;
    }

}