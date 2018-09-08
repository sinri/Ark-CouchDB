<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 18:20
 */

namespace sinri\ark\database\couchdb\api;


use sinri\ark\database\couchdb\io\ArkCouchDBRequest;
use sinri\ark\database\couchdb\io\ArkCouchDBResponse;

class ArkCouchDBDocumentApi extends ArkCouchDBAbstractApi
{
    /**
     * Creates a new document in the specified database, using the supplied JSON document structure.
     *
     * If the JSON structure includes the _id field, then the document will be created with the specified document ID.
     *
     * If the _id field is not specified, a new unique ID will be generated, following whatever UUID algorithm is configured for that server.
     *
     * @see http://docs.couchdb.org/en/latest/api/database/common.html#post--db
     * @param string $db
     * @param array $doc
     * @param null|"ok" $batchMode
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function createDocumentInDatabase($db, $doc, $batchMode = null)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_POST, "/" . urlencode($db));
        if ($batchMode !== null) {
            $request->setQueries(["batch" => $batchMode]);
        }
        $request->setBodyAndType($doc);
        $response = $this->callApi($request);
        return $response;
//        switch ($response->responseHTTPCode()) {
//            case 201: // Document created and stored on disk
//            case 202: // Document data accepted, but not yet stored on disk
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 404:
//            case 409:
//                $this->error = $response->getParsedItemByIndex(['error']);
//                $this->errorReason = $response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error = "Unknown response code: " . $response->responseHTTPCode();
//                $this->errorReason = "as error";
//                return false;
//        }
    }

    /**
     * Returns the HTTP Headers containing a minimal amount of information about the specified document. The method supports the same query arguments as the GET /{db}/{docid} method, but only the header information (including document size, and the revision as an ETag), is returned.
     *
     * The ETag header shows the current revision for the requested document, and the Content-Length specifies the length of the data, if the document were requested in full.
     *
     * Adding any of the query arguments (see GET /{db}/{docid}), then the resulting HTTP Headers will correspond to what would be returned.
     *
     * @see http://docs.couchdb.org/en/latest/api/document/common.html#head--db-docid
     * @param string $db
     * @param string $docId
     * @param null $revId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function headDocument($db, $docId, $revId = null)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_HEAD, "/" . urlencode($db) . "/" . urlencode($docId));
        if ($revId !== null) {
            $request->setHeaders([
                "If-None-Match" => '"' . $revId . '"',
            ]);
        }
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Returns document by the specified docid from the specified db. Unless you request a specific revision, the latest revision of the document will always be returned.
     *
     * Query Parameters:
     *
     *  attachments (boolean) – Includes attachments bodies in response. Default is false
     *  att_encoding_info (boolean) – Includes encoding information in attachment stubs if the particular attachment is compressed. Default is false.
     *  atts_since (array) – Includes attachments only since specified revisions. Does not includes attachments for specified revisions. Optional
     *  conflicts (boolean) – Includes information about conflicts in document. Default is false
     *  deleted_conflicts (boolean) – Includes information about deleted conflicted revisions. Default is false
     *  latest (boolean) – Forces retrieving latest “leaf” revision, no matter what rev was requested. Default is false
     *  local_seq (boolean) – Includes last update sequence for the document. Default is false
     *  meta (boolean) – Acts same as specifying all conflicts, deleted_conflicts and revs_info query parameters. Default is false
     *  open_revs (array) – Retrieves documents of specified leaf revisions. Additionally, it accepts value as all to return all leaf revisions. Optional
     *  rev (string) – Retrieves document of specified revision. Optional
     *  revs (boolean) – Includes list of all known document revisions. Default is false
     *  revs_info (boolean) – Includes detailed information for all known document revisions. Default is false
     *
     * @see http://docs.couchdb.org/en/latest/api/document/common.html#get--db-docid
     * @param string $db
     * @param string $docId
     * @param null|array $queries
     * @param null|string $revId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getDocument($db, $docId, $queries = null, $revId = null)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/" . urlencode($db) . "/" . urlencode($docId));
        if ($revId !== null) {
            $request->setHeaders([
                "If-None-Match" => '"' . $revId . '"',
            ]);
        }
        if (is_array($queries)) {
            $request->setQueries($queries);
        }
        $response = $this->callApi($request);
        return $response;
//        switch ($response->responseHTTPCode()) {
//            case 200:
//                return $response->getParsed();
//            case 304:
//            case 400:
//            case 401:
//            case 404:
//                $this->error = $response->getParsedItemByIndex(['error']);
//                $this->errorReason = $response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error = "Unknown response code: " . $response->responseHTTPCode();
//                $this->errorReason = "as error";
//                return false;
//        }
    }

    /**
     * The PUT method creates a new named document, or creates a new revision of the existing document. Unlike the POST /{db}, you must specify the document ID in the request URL.
     *
     * When updating an existing document, the current document revision must be included in the document (i.e. the request body), as the rev query parameter, or in the If-Match request header.
     *
     * @see http://docs.couchdb.org/en/latest/api/document/common.html#put--db-docid
     * @param string $db
     * @param string $docId
     * @param array $doc
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function writeDocument($db, $docId, $doc)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_PUT, "/" . urlencode($db) . "/" . urlencode($docId));
        $request->setBodyAndType($doc);
        $response = $this->callApi($request);
        return $response;
//        switch ($response->responseHTTPCode()) {
//            case 201:
//            case 202:
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 404:
//            case 409:
//                $this->error = $response->getParsedItemByIndex(['error']);
//                $this->errorReason = $response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error = "Unknown response code: " . $response->responseHTTPCode();
//                $this->errorReason = "as error";
//                return false;
//        }
    }

    /**
     * Marks the specified document as deleted by adding a field _deleted with the value true. Documents with this field will not be returned within requests anymore, but stay in the database. You must supply the current (latest) revision, either by using the rev parameter or by using the If-Match header to specify the revision.
     *
     * Note: CouchDB doesn’t completely delete the specified document. Instead, it leaves a tombstone with very basic information about the document. The tombstone is required so that the delete action can be replicated across databases.
     *
     * @see http://docs.couchdb.org/en/latest/api/document/common.html#delete--db-docid
     * @param string $db
     * @param string $docId
     * @param null|string $rev
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function deleteDocument($db, $docId, $rev = null)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_DELETE, "/" . urlencode($db) . "/" . urlencode($docId));
        if ($rev !== null) {
            $request->setQueries(['rev' => $rev]);
        }
        $response = $this->callApi($request);
        return $response;
//        switch ($response->responseHTTPCode()) {
//            case 200:
//            case 202:
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 404:
//            case 409:
//                $this->error = $response->getParsedItemByIndex(['error']);
//                $this->errorReason = $response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error = "Unknown response code: " . $response->responseHTTPCode();
//                $this->errorReason = "as error";
//                return false;
//        }
    }

    /**
     * The COPY (which is non-standard HTTP) copies an existing document to a new or existing document. Copying a document is only possible within the same database.
     *
     * The source document is specified on the request line, with the Destination header of the request specifying the target document.
     *
     * @see http://docs.couchdb.org/en/latest/api/document/common.html#copy--db-docid
     * @param string $db
     * @param string $docId
     * @param string $destination Destination document. Must contain the target document ID, and optionally the target document revision, if copying to an existing document. See Copying to an Existing Document.
     * @param null $rev
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function copyDocument($db, $docId, $destination, $rev = null)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_COPY, "/" . urlencode($db) . "/" . urlencode($docId));
        if ($rev !== null) {
            $request->setQueries(['rev' => $rev]);
        }
        $request->setHeaders([
            "Destination" => $destination,
        ]);
        $response = $this->callApi($request);
        return $response;
//        switch ($response->responseHTTPCode()) {
//            case 201:
//            case 202:
//                return $response->getParsed();
//            case 400:
//            case 401:
//            case 404:
//            case 409:
//                $this->error = $response->getParsedItemByIndex(['error']);
//                $this->errorReason = $response->getParsedItemByIndex(['reason']);
//                return false;
//            default:
//                $this->error = "Unknown response code: " . $response->responseHTTPCode();
//                $this->errorReason = "as error";
//                return false;
//        }
    }

    // Attachment is not in plan
    // @see http://docs.couchdb.org/en/latest/api/document/common.html#attachments
}