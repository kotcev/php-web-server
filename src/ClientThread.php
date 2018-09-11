<?php

namespace Kotcev\Server;


class ClientThread extends \Thread
{

    /**
     * Socket resource.
     *
     * @var
     */
    protected $socket;

    /**
     * The web request.
     *
     * @var Request
     */
    protected $request;

    public function __construct($socket, Request $request)
    {
        $this->socket = $socket;
        $this->request = $request;

        $this->start();
    }

    /**
     *
     */
    public function run()
    {
        socket_write($this->socket, "HTTP/1.1 200 OK" . "\r\n\r\n" . (string) microtime(true));

        socket_close($this->socket);
    }

}