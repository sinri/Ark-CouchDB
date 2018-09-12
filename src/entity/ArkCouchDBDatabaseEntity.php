<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 21:38
 */

namespace sinri\ark\database\couchdb\entity;


use sinri\ark\database\couchdb\ArkCouchDBAgent;

abstract class ArkCouchDBDatabaseEntity
{
    /**
     * @var ArkCouchDBAgent
     */
    //protected $agent;
    /**
     * @var string
     */
    //protected $db;

    public function __construct()
    {
    }

//    /**
//     * @param ArkCouchDBAgent $agent
//     * @param string $db
//     * @return ArkCouchDBDatabaseEntity
//     * @throws \Exception
//     */
//    private static function createDatabaseAndGetEntity($agent, $db)
//    {
//        $response = $agent->getApiForDatabase()->createDatabase($db);
//        if (!$response->is2XX()) {
//            throw new \Exception($response->errorString());
//        }
//
//        $entity = new ArkCouchDBDatabaseEntity();
//        $entity->agent = $agent;
//        $entity->db = $db;
//        return $entity;
//    }

//    /**
//     * @param ArkCouchDBAgent $agent
//     * @param string $db
//     * @param bool $createIfNotExist
//     * @return ArkCouchDBDatabaseEntity
//     * @throws \Exception
//     */
//    public static function openDatabaseAndGetEntity($agent, $db, $createIfNotExist = false)
//    {
//        if (!$agent->getApiForDatabase()->headDatabase($db)->is2XX()) {
//            if ($createIfNotExist) return self::createDatabaseAndGetEntity($agent, $db);
//            else throw new \Exception("Database does not exist");
//        }
//        $entity = new ArkCouchDBDatabaseEntity();
//        $entity->agent = $agent;
//        $entity->db = $db;
//        return $entity;
//    }

    /**
     * @return ArkCouchDBAgent
     */
    abstract public function agent();

    /**
     * @return string
     */
    abstract public function db();

    /**
     * @throws \Exception
     */
    public function ensureDatabaseExists()
    {
        if (!$this->agent()->getApiForDatabase()->headDatabase($this->db())->is2XX()) {
            $response = $this->agent()->getApiForDatabase()->createDatabase($this->db());
            if (!$response->is2XX()) {
                throw new \Exception($response->errorString());
            }
        }
    }

    /**
     * @param ArkCouchDBDocumentEntity $doc
     * @return ArkCouchDBDocumentEntity
     * @throws \Exception
     */
    public function writeDocument($doc)
    {
        if ($doc->getId())
            $response = $this->agent()->getApiForDocument()->writeDocument($this->db(), $doc->getId(), $doc->getProperties());
        else
            $response = $this->agent()->getApiForDocument()->createDocumentInDatabase($this->db(), $doc->getProperties());

        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }

        $docId = $response->getParsedItemByIndex(['id']);
        $doc->loadExistDocument($docId);
        return $doc;
    }

    /**
     * @param array $selector JSON object describing criteria used to select documents. More information provided in the section on selector syntax. @see http://docs.couchdb.org/en/latest/api/database/find.html#find-selectors
     * @param int $limit Maximum number of results returned. Default is 25.
     * @param int $skip Skip the first ‘n’ results, where ‘n’ is the value specified.
     * @param null|array $sort JSON array following sort syntax. @see http://docs.couchdb.org/en/latest/api/database/find.html#find-sort
     * @param null|array $fields JSON array specifying which fields of each object should be returned. If it is omitted, the entire object is returned. More information provided in the section on filtering fields. @see http://docs.couchdb.org/en/latest/api/database/find.html#find-filter
     * @param null|string|array $index Instruct a query to use a specific index. Specified either as "<design_document>" or ["<design_document>", "<index_name>"].
     * @return array
     * @throws \Exception
     */
    public function findDocuments($selector, $limit = 10, $skip = 0, $sort = null, $fields = null, $index = null)
    {
        $options = [
            'selector' => (object)$selector,
            'limit' => $limit,
            'skip' => $skip,
        ];
        if ($sort !== null) {
            $options['sort'] = (object)$sort;
        }
        if ($fields !== null) {
            $options['fields'] = $fields;
        }
        if ($index !== null) {
            $options['use_index'] = $index;
        }
        $response = $this->agent()->getApiForDocument()->findDocuments($this->db(), $options);
        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }
        $docs = $response->getParsedItemByIndex(['docs']);
        return $docs;
    }
}