<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 13:20
 */

namespace sinri\ark\database\couchdb\api;


use sinri\ark\core\ArkLogger;
use sinri\ark\database\couchdb\io\ArkCouchDBRequest;
use sinri\ark\database\couchdb\io\ArkCouchDBResponse;
use sinri\ark\io\curl\ArkCurl;

abstract class ArkCouchDBAbstractApi
{
    protected $baseURL;

    public function __construct($baseApi)
    {
        $this->baseURL = $baseApi;
    }

    /**
     * @return mixed
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * @param ArkCouchDBRequest $request
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function callApi($request)
    {
        $req = new ArkCurl();
        $req->setLogger(new ArkLogger());
        $req->prepareToRequestURL($request->getMethod(), $this->baseURL . $request->getApi());

        // default headers
        $req->setHeader("Accept", "application/json");

        // header override
        if (is_array($request->getHeaders())) {
            foreach ($request->getHeaders() as $headerName => $headerValue) {
                $req->setHeader($headerName, $headerValue);
            }
        }

        // queries
        if (is_array($request->getQueries())) {
            foreach ($request->getQueries() as $queryName => $queryValue) {
                $req->setQueryField($queryName, $queryValue);
            }
        }

        // body
        switch ($request->getBodyType()) {
            case ArkCouchDBRequest::BODY_AS_EMPTY:
                $result = $req->execute();
                break;
            case ArkCouchDBRequest::BODY_AS_JSON:
                $req->setPostContent($request->getBody());
                $result = $req->execute(true);
                break;
            case ArkCouchDBRequest::BODY_AS_STRING:
                $req->setPostContent($request->getBody());
                $result = $req->execute();
                break;
            default:
                throw new \Exception("Body Type Invalid");

        }

        return new ArkCouchDBResponse($result, $req->getResponseMeta(), $req->getResponseHeaders());
    }

}