<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 12:41
 */

namespace sinri\ark\database\couchdb;


use sinri\ark\database\couchdb\api\ArkCouchDBDatabaseApi;
use sinri\ark\database\couchdb\api\ArkCouchDBDesignDocApi;
use sinri\ark\database\couchdb\api\ArkCouchDBDocumentApi;
use sinri\ark\database\couchdb\api\ArkCouchDBServerApi;

class ArkCouchDBAgent
{
    protected $baseURL;

    public function __construct($host, $port, $username = null, $password = null)
    {
        $this->baseURL = "";
        if ($username !== null && $password !== null) {
            $this->baseURL .= $username . ":" . $password . "@";
        }
        $this->baseURL .= $host . ":" . $port;
    }

    /**
     * @return ArkCouchDBDesignDocApi
     */
    public function getApiForDesignDoc()
    {
        return new ArkCouchDBDesignDocApi($this->baseURL);
    }

    /**
     * @return ArkCouchDBDocumentApi
     */
    public function getApiForDocument()
    {
        return new ArkCouchDBDocumentApi($this->baseURL);
    }

    /**
     * @return ArkCouchDBDatabaseApi
     */
    public function getApiForDatabase()
    {
        return new ArkCouchDBDatabaseApi($this->baseURL);
    }

    /**
     * @return ArkCouchDBServerApi
     */
    public function getApiForServer()
    {
        return new ArkCouchDBServerApi($this->baseURL);
    }


}