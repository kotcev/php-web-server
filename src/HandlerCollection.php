<?php

namespace Kotcev\Server;


class HandlerCollection
{

    /**
     * @var array
     */
    private $handlers;

    /**
     * HandlerCollection constructor.
     * @param array $handlers
     */
    public function __construct(array $handlers = array())
    {
        $this->handlers = $handlers;
    }

    /**
     * @param $uriPath
     * @param callable $handler
     */
    public function registerHandler($uriPath, callable $handler) : void
    {
        $this->handlers[$uriPath] = $handler;
    }

    /**
     * @param $uriPath
     * @return Response
     */
    public function callHandler($uriPath)
    {
        if (! isset($this->handlers[$uriPath])) {
            return new Response("404 Not found!", 404);
        }

        $handlerReturnValue = $this->handlers[$uriPath]();

        if ($handlerReturnValue instanceof Response) {
            return $handlerReturnValue;
        }

        return new Response((string) $handlerReturnValue, 200);
    }

    /**
     * @param $uriPath
     */
    public function removeHandler($uriPath) : void
    {
        unset($this->handlers[$uriPath]);
    }

}