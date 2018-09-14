<?php

namespace Kotcev\Server;


class HandlerDispatcher
{

    /**
     * @var HandlerCollection
     */
    private $handlerCollection;

    /**
     * @var Request
     */
    private $request;

    /**
     * HandlerDispatcher constructor.
     * @param HandlerCollection $handlerCollection
     * @param Request $request
     */
    public function __construct(HandlerCollection $handlerCollection, Request $request)
    {
        $this->handlerCollection = $handlerCollection;
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function dispatch() : Response
    {
        return $this->handlerCollection->callHandler($this->request->getUriPath());
    }

}