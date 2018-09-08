<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 13:09
 */

namespace sinri\ark\database\couchdb\io;


use sinri\ark\core\ArkHelper;

class ArkCouchDBResponse
{
    protected $responseMeta;
    protected $responseHeaders;// only for HEAD
    protected $rawResponseString;
    protected $parsed;

    public function __construct($raw, $meta = null, $headers = null)
    {
        $this->rawResponseString = $raw;
        $this->parsed = json_decode($raw, true);
        $this->responseMeta = $meta;
        $this->responseHeaders = $headers;
    }

    /**
     * @return string|bool
     */
    public function getRawResponseString()
    {
        return $this->rawResponseString;
    }

    /**
     * @return array|bool
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * @param array|string $keyChain
     * @param null $default
     * @return mixed|null
     */
    public function getParsedItemByIndex($keyChain, $default = null)
    {
        return ArkHelper::readTarget($this->parsed, $keyChain, $default);
    }

    /**
     * @return int|null
     */
    public function responseHTTPCode()
    {
        return ArkHelper::readTarget($this->responseMeta, ['http_code'], -1);
    }

    /**
     * @return bool
     */
    public function is2XX()
    {
        return $this->responseHTTPCode() >= 200 && $this->responseHTTPCode() <= 299;
    }

    /**
     * @return string
     */
    public function errorString()
    {
        return "Response code is " . $this->responseHTTPCode()
            . ". Error is " . $this->getParsedItemByIndex("error")
            . ". Reason is " . $this->getParsedItemByIndex("reason");
    }
}