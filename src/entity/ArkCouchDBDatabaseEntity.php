<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 21:38
 */

namespace sinri\ark\database\couchdb\entity;


use sinri\ark\database\couchdb\ArkCouchDBAgent;

class ArkCouchDBDatabaseEntity
{
    /**
     * @var ArkCouchDBAgent
     */
    protected $agent;
    /**
     * @var string
     */
    protected $db;

    public function __construct()
    {
    }

    /**
     * @param ArkCouchDBAgent $agent
     * @param string $db
     * @return ArkCouchDBDatabaseEntity
     * @throws \Exception
     */
    private static function createDatabaseAndGetEntity($agent, $db)
    {
        $response = $agent->getApiForDatabase()->createDatabase($db);
        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }

        $entity = new ArkCouchDBDatabaseEntity();
        $entity->agent = $agent;
        $entity->db = $db;
        return $entity;
    }

    /**
     * @param ArkCouchDBAgent $agent
     * @param string $db
     * @param bool $createIfNotExist
     * @return ArkCouchDBDatabaseEntity
     * @throws \Exception
     */
    public static function openDatabaseAndGetEntity($agent, $db, $createIfNotExist = false)
    {
        if (!$agent->getApiForDatabase()->headDatabase($db)->is2XX()) {
            if ($createIfNotExist) return self::createDatabaseAndGetEntity($agent, $db);
            else throw new \Exception("Database does not exist");
        }
        $entity = new ArkCouchDBDatabaseEntity();
        $entity->agent = $agent;
        $entity->db = $db;
        return $entity;
    }

    public function writeDocument($doc)
    {
        if (isset($doc['_id']))
            $response = $this->agent->getApiForDocument()->writeDocument($this->db, $doc['_id'], $doc);
        else
            $response = $this->agent->getApiForDocument()->createDocumentInDatabase($this->db, $doc);

        if (!$response->is2XX()) {
            return false;
        }

        return $response->getParsed();//would have three fields, `id`,`ok`,`rev`
    }

}