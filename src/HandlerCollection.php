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
        $uriPath = $this->addTrailingSlash($uriPath);

        $this->handlers[$uriPath] = $handler;
    }

    /**
     * @param $uriPath
     * @param Request $request
     * @return Response
     */
    public function callHandler($uriPath, Request $request)
    {
        $uriPath = $this->addTrailingSlash($uriPath);

        if ( ! isset($this->handlers[$uriPath])) {
            return new Response("404 Not found!", 404);
        }

        $handlerReturnValue = $this->handlers[$uriPath]($request);

        if ($handlerReturnValue instanceof Response) {
            return $handlerReturnValue;
        }

        return new Response((string) $handlerReturnValue, 200);
    }

    /**
     * @param $uriPath
     * @return string
     */
    private function addTrailingSlash($uriPath)
    {
        return $uriPath{-1} !== '/' ? $uriPath . '/' : $uriPath;
    }

    /**
     * @param $uriPath
     */
    public function removeHandler($uriPath) : void
    {
        unset($this->handlers[$uriPath]);
    }

}