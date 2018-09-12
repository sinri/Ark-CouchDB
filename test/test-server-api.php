<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 13:38
 */

use sinri\ark\database\couchdb\test\TestFacade;

require_once __DIR__ . '/test-include.php';

$serverApi = $agent->getApiForServer();

$couchDBInstanceMetaInfo = $serverApi->getCouchDBInstanceMetaInfo();
TestFacade::show('$couchDBInstanceMetaInfo', $couchDBInstanceMetaInfo);
//echo json_encode($couchDBInstanceMetaInfo,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

$activeTasks = $serverApi->getActiveTasks();
TestFacade::show('$activeTasks', $activeTasks);

$databases = $serverApi->getAllDatabases();
TestFacade::show('$databases', $databases);

$dbsInfo = $serverApi->batchQueryDatabaseInfo(["fountain"]);
TestFacade::show('$dbsInfo', $dbsInfo);

$memberNodes = $serverApi->getMemberShip();
TestFacade::show('$memberNodes', $memberNodes);

$uuids = $serverApi->getUUIDs(2);
TestFacade::show('$uuids', $uuids);
