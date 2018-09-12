<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/12
 * Time: 11:45
 */

namespace sinri\ark\database\couchdb\test;


use sinri\ark\database\couchdb\ArkCouchDBAgent;
use sinri\ark\database\couchdb\entity\ArkCouchDBDocumentEntity;

class SinriTestDocument extends ArkCouchDBDocumentEntity
{

    /**
     * @return ArkCouchDBAgent
     */
    public function agent()
    {
        return TestFacade::getTestAgent();
    }

    /**
     * @return string
     */
    public function db()
    {
        return "sinri_test";
    }
}