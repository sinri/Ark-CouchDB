<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 14:40
 */

require_once __DIR__ . '/test-include.php';

$api = $agent->getApiForDatabase();

$db_name = "sinri_test";

$exists = $api->headDatabase($db_name);
show("sinri_test exists:", $exists);

$not_exists = $api->headDatabase($db_name . "_no_this");
show("sinri_test not exists:", $not_exists);

$db_info = $api->getDatabase($db_name);
show('$db_info', $db_info);