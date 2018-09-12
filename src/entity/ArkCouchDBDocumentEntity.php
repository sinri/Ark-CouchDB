<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/10
 * Time: 23:15
 */

namespace sinri\ark\database\couchdb\entity;


use sinri\ark\core\ArkHelper;
use sinri\ark\database\couchdb\ArkCouchDBAgent;

/**
 * Class ArkCouchDBDocumentEntity
 * @package sinri\ark\database\couchdb\entity
 */
abstract class ArkCouchDBDocumentEntity
{
    /**
     * @var ArkCouchDBAgent
     */
    //protected $agent;
    /**
     * @var string
     */
    //protected $db;
    /**
     * @var array
     */
    protected $properties;

    /**
     * ArkCouchDBDocumentEntity constructor.
     * @param null|array $doc
     */
    public function __construct($doc = null)
    {
        //$this->agent=$agent;
        //$this->db=$db;
        $this->properties = [];
        if ($doc) {
            foreach ($doc as $key => $value) {
                $this->properties[$key] = $value;
            }
        }
    }

    /**
     * @return ArkCouchDBAgent
     */
    abstract public function agent();

    /**
     * @return string
     */
    abstract public function db();

    /**
     * @param string $docId
     * @throws \Exception
     */
    public function loadExistDocument($docId)
    {
        $response = $this->agent()->getApiForDocument()->getDocument($this->db(), $docId);
        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }
        $this->properties = $response->getParsed();
        //return new ArkCouchDBDocumentEntity($response->getParsed());
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->properties);
    }

    /**
     * @param string|array $name
     * @return mixed|null
     */
    public function property($name)
    {
        return ArkHelper::readTarget($this->properties, $name);
    }

    /**
     * string|array
     * @param $name
     * @param $value
     */
    public function modifyProperty($name, $value)
    {
        ArkHelper::writeIntoArray($this->properties, $name, $value);
    }

    /**
     * @param string|array $name
     */
    public function removeProperty($name)
    {
        ArkHelper::removeFromArray($this->properties, $name);
    }

    public function setId($newId)
    {
        $this->modifyProperty("_id", $newId);
    }

    public function setRev($newRev)
    {
        $this->modifyProperty("_rev", $newRev);
    }

    public function getId()
    {
        return $this->property("_id");
    }

    public function getRev()
    {
        return $this->property("_rev");
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function update()
    {
        $response = $this->agent()->getApiForDocument()->writeDocument($this->db(), $this->getId(), $this->properties);
        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }
        $this->setId($response->getParsedItemByIndex(["id"]));
        $this->setRev($response->getParsedItemByIndex(["rev"]));
        return $response->getParsedItemByIndex(['ok']);
    }

    /**
     * @param bool $limitToThisRev
     * @return bool
     * @throws \Exception
     */
    public function delete($limitToThisRev = false)
    {
        if ($limitToThisRev) {
            $response = $this->agent()->getApiForDocument()->deleteDocument($this->db(), $this->getId(), $this->getRev());
        } else {
            $response = $this->agent()->getApiForDocument()->deleteDocument($this->db(), $this->getId());
        }
        if (!$response->is2XX()) {
            throw new \Exception($response->errorString());
        }
        $this->modifyProperty("_delete", true);
        return $response->getParsedItemByIndex(["ok"]);
    }
}