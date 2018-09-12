<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 14:40
 */

use sinri\ark\database\couchdb\test\TestFacade;

require_once __DIR__ . '/test-include.php';

$api = $agent->getApiForDatabase();

$db_name = "sinri_test";

$exists = $api->headDatabase($db_name);
TestFacade::show("sinri_test exists:", $exists);

$not_exists = $api->headDatabase($db_name . "_no_this");
TestFacade::show("sinri_test not exists:", $not_exists);

$db_info = $api->getDatabase($db_name);
TestFacade::show('$db_info', $db_info);