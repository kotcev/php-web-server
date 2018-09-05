<?php

namespace Kotcev\Server;

class Request
{

    /**
     * The request method.
     *
     * @var string
     */
    protected $method;

    /**
     * The request protocol.
     *
     * @var string
     */
    protected $protocol;

    /**
     * The request uri.
     *
     * @var string
     */
    protected $uri;


    /**
     * Associative headers array.
     *
     * @var array
     */
    protected $headers = array();

    public function __construct(string $method, string $uri, array $headers, string $protocol = "HTTP/1.1")
    {
        $this->method = $method;
        $this->headers = $headers;
        $this->protocol = $protocol;
        $this->uri = $uri;
    }

    /**
     * @param string $headerString
     * @return Request
     */
    public static function makeFromHeaderString(string $headerString) : self
    {
        $headers = explode("\r\n", $headerString);

        $methodHttpVersionString = array_shift($headers);

        list($method, $uri, $protocol) = array_map('trim', explode(" ", $methodHttpVersionString));

        $headers = self::parseHeadersToAssoc($headers);

        return new self($method, $uri, $headers, $protocol);
    }

    /**
     * Takes normal headers array , then parses it to associative
     *
     * @param $headers
     * @return array
     */
    private static function parseHeadersToAssoc(array $headers) : array
    {
        $headersAssoc = array();

        foreach ($headers as $header) {

            if ( ! empty($header)) {

                list($hKey, $hVal) = explode(":", $header, 2);

                array_push($headersAssoc, [trim($hKey) => trim($hVal)]);
            }
        }

        return $headersAssoc;
    }

}