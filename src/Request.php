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
     * The request path.
     *
     * @var string
     */
    protected $uriPath;

    /**
     * The query string.
     *
     * @var string
     */
    protected $query;

    /**
     * The query string parameters.
     *
     * @var array
     */
    protected $queryParameters = array();

    /**
     * Associative headers array.
     *
     * @var array
     */
    protected $headers = array();

    public function __construct(string $method, string $uriPath, array $headers, string $protocol = "HTTP/1.1")
    {
        $this->method = $method;
        $this->headers = $headers;
        $this->protocol = $protocol;
        $this->uriPath = $uriPath;

        $this->parseUriPath();
    }

    /**
     * @param string $headerString
     * @return Request
     */
    public static function makeFromHeaderString(string $headerString) : self
    {
        $headers = explode("\r\n", $headerString);

        $methodHttpVersionString = array_shift($headers);

        list($method, $uriPath, $protocol) = array_map('trim', explode(" ", $methodHttpVersionString));

        $headers = self::parseHeadersToAssoc($headers);

        return new self($method, $uriPath, $headers, $protocol);
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

    /**
     *
     */
    private function parseUriPath()
    {
        $cutUriPath = explode("?", $this->uriPath, 2);

        $this->query = isset($cutUriPath[1]) ? $cutUriPath[1] : '';

        $this->parseQueryParameters();
    }

    /**
     * Parses the query string parameters
     * to associative array.
     */
    private function parseQueryParameters()
    {
        $parameters = explode("&", $this->query);

        foreach ($parameters as $parameter) {

            if ( ! empty($parameter)) {

                $parameter = explode("=", $parameter, 2);

                array_push($this->queryParameters, [
                    $parameter[0] => isset($parameter[1]) ? $parameter[1] : ""
                ]);
            }
        }
    }

    /**
     * @return string
     */
    public function getUriPath() : string
    {
        return $this->uriPath;
    }
}