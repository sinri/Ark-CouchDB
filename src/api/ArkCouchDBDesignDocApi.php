<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 18:23
 */

namespace sinri\ark\database\couchdb\api;


use sinri\ark\database\couchdb\io\ArkCouchDBRequest;
use sinri\ark\database\couchdb\io\ArkCouchDBResponse;

class ArkCouchDBDesignDocApi extends ArkCouchDBAbstractApi
{
    /**
     * Returns the contents of the design document specified with the name of the design document and from the specified database from the URL. Unless you request a specific revision, the latest revision of the document will always be returned.
     *
     * @see http://docs.couchdb.org/en/latest/api/ddoc/common.html#get--db-_design-ddoc
     * @param string $db
     * @param string $designDocId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getDesignDoc($db, $designDocId)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/" . urlencode($db) . '/_design/' . urlencode($designDocId));
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * The PUT method creates a new named design document, or creates a new revision of the existing design document.
     *
     * The design documents have some agreement upon their fields and structure. Currently it is the following:
     *  language (string): Defines Query Server key to process design document functions
     *  options (object): View’s default options
     *  filters (object): Filter functions definition
     *  lists (object): List functions definition
     *  rewrites (array or string): Rewrite rules definition
     *  shows (object): Show functions definition
     *  updates (object): Update functions definition
     *  validate_doc_update (string): Validate document update function source
     *  views (object): View functions definition.
     *
     * Note, that for filters, lists, shows and updates fields objects are mapping of function name to string function source code. For views mapping is the same except that values are objects with map and reduce (optional) keys which also contains functions source code.
     *
     * @see http://docs.couchdb.org/en/latest/api/ddoc/common.html#put--db-_design-ddoc
     * @param string $db
     * @param string $designDocId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function writeDesignDoc($db, $designDocId)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_PUT, "/" . urlencode($db) . '/_design/' . urlencode($designDocId));
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Deletes the specified document from the database. You must supply the current (latest) revision, either by using the rev parameter to specify the revision.
     *
     * @see http://docs.couchdb.org/en/latest/api/ddoc/common.html#delete--db-_design-ddoc
     * @param string $db
     * @param string $designDocId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function deleteDesignDoc($db, $designDocId)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_DELETE, "/" . urlencode($db) . '/_design/' . urlencode($designDocId));
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * The COPY (which is non-standard HTTP) copies an existing design document to a new or existing one.
     *
     * Given that view indexes on disk are named after their MD5 hash of the view definition, and that a COPY operation won’t actually change that definition, the copied views won’t have to be reconstructed. Both views will be served from the same index on disk.
     *
     * @see http://docs.couchdb.org/en/latest/api/ddoc/common.html#copy--db-_design-ddoc
     * @param $db
     * @param $designDocId
     * @param $destination
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function copyDesignDoc($db, $designDocId, $destination)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_COPY, "/" . urlencode($db) . '/_design/' . urlencode($designDocId));
        $request->setHeaders([
            "Destination" => $destination,
        ]);
        $response = $this->callApi($request);
        return $response;
    }

    // design doc attachment is not in plan
    // @see http://docs.couchdb.org/en/latest/api/ddoc/common.html#db-design-design-doc-attachment

    /**
     * @param $db
     * @param $designDocId
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getDesignDocInfo($db, $designDocId)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/" . urlencode($db) . '/_design/' . urlencode($designDocId) . '/_info');
        $response = $this->callApi($request);
        return $response;
    }
}