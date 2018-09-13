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

    /**
     * The web response;
     *
     * @var Response
     */
    protected $response;

    public function __construct($socket, Request $request, Response $response)
    {
        $this->socket = $socket;
        $this->request = $request;
        $this->response = $response;

        $this->start();
    }

    /**
     *
     */
    public function run()
    {
        socket_write($this->socket, $this->response->getResponseString());

        socket_close($this->socket);
    }

}