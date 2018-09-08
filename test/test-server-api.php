<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 13:38
 */

require_once __DIR__ . '/test-include.php';

$serverApi = $agent->getApiForServer();

$couchDBInstanceMetaInfo = $serverApi->getCouchDBInstanceMetaInfo();
show('$couchDBInstanceMetaInfo', $couchDBInstanceMetaInfo);
//echo json_encode($couchDBInstanceMetaInfo,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

$activeTasks = $serverApi->getActiveTasks();
show('$activeTasks', $activeTasks);

$databases = $serverApi->getAllDatabases();
show('$databases', $databases);

$dbsInfo = $serverApi->batchQueryDatabaseInfo(["fountain"]);
show('$dbsInfo', $dbsInfo);

$memberNodes = $serverApi->getMemberShip();
show('$memberNodes', $memberNodes);

$uuids = $serverApi->getUUIDs(2);
show('$uuids', $uuids);
