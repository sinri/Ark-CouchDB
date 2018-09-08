<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 12:46
 */

namespace sinri\ark\database\couchdb\io;


class ArkCouchDBRequest
{
    const METHOD_GET = "GET";
    const METHOD_HEAD = "HEAD";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_DELETE = "DELETE";
    const METHOD_COPY = "COPY";

    const BODY_AS_EMPTY = "EMPTY";
    const BODY_AS_JSON = "JSON";
    const BODY_AS_STRING = "STRING";

    protected $api;
    protected $method;
    protected $headers;
    protected $queries;
    protected $body;
    protected $bodyType;

    public function __construct($method, $api)
    {
        $this->api = $api;
        $this->method = $method;
        $this->headers = [];
        $this->queries = [];
        $this->body = null;
        $this->bodyType = self::BODY_AS_EMPTY;
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @param array $queries
     */
    public function setQueries(array $queries)
    {
        $this->queries = $queries;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param mixed $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @param mixed $body
     * @param string $bodyType
     */
    public function setBodyAndType($body, $bodyType = null)
    {
        $this->body = $body;
        if ($bodyType === null) {
            $bodyType = self::BODY_AS_EMPTY;
            if (is_array($body) || is_object($body)) $bodyType = self::BODY_AS_JSON;
            elseif (is_scalar($body)) $bodyType = self::BODY_AS_STRING;
        }
        $this->bodyType = $bodyType;
    }
}