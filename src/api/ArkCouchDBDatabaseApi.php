<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 14:35
 */

namespace sinri\ark\database\couchdb\api;

use sinri\ark\database\couchdb\io\ArkCouchDBRequest;
use sinri\ark\database\couchdb\io\ArkCouchDBResponse;

/**
 * The Database endpoint provides an interface to an entire database with in CouchDB. These are database-level, rather than document-level requests.
 *
 * Class ArkCouchDBDatabaseApi
 * @package sinri\ark\database\couchdb\api
 */
class ArkCouchDBDatabaseApi extends ArkCouchDBAbstractApi
{
    /**
     * Returns the HTTP Headers containing a minimal amount of information about the specified database. Since the response body is empty, using the HEAD method is a lightweight way to check if the database exists already or not.
     *
     * @see http://docs.couchdb.org/en/latest/api/database/common.html#head--db
     * @param string $db
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function headDatabase($db)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_HEAD, "/" . urlencode($db));
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Gets information about the specified database.
     *
     * @see http://docs.couchdb.org/en/latest/api/database/common.html#get--db
     * @param string $db
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getDatabase($db)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/" . urlencode($db));
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Creates a new database. The database name {db} must be composed by following next rules:
     *
     * @see http://docs.couchdb.org/en/latest/api/database/common.html#put--db
     * @param string $db
     * @param int|null $q Shards, aka the number of range partitions. Default is 8, unless overridden in the cluster config.
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function createDatabase($db, $q = null)
    {
        if (!preg_match('/^[a-z][a-z0-9_$()+/-]*$/', $db)) {
            throw new \Exception("Database name illegal!");
        }
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_PUT, "/" . urlencode($db));
        if ($q) {
            $request->setQueries(["q" => $q]);
        }
        $response = $this->callApi($request);
        return $response;
//        switch($response->responseHTTPCode()){
//            case 201:
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 412:
//                $this->error=$response->getParsedItemByIndex(['error']);
//                $this->errorReason=$response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error="Unknown response code: ".$response->responseHTTPCode();
//                $this->errorReason="as error";
//                return false;
//        }
    }

    /**
     * Deletes the specified database, and all the documents and attachments contained within it.
     *
     * Note: To avoid deleting a database, CouchDB will respond with the HTTP status code 400 when the request URL includes a ?rev= parameter. This suggests that one wants to delete a document but forgot to add the document id to the URL.
     *
     * @see http://docs.couchdb.org/en/latest/api/database/common.html#delete--db
     * @param $db
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function deleteDatabase($db)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_DELETE, "/" . urlencode($db));
        $response = $this->callApi($request);
        return $response;
//        switch($response->responseHTTPCode()){
//            case 201:
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 404:
//                $this->error=$response->getParsedItemByIndex(['error']);
//                $this->errorReason=$response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error="Unknown response code: ".$response->responseHTTPCode();
//                $this->errorReason="as error";
//                return false;
//        }
    }
}