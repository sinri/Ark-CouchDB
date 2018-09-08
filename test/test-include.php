<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 14:41
 */

require_once __DIR__ . '/../vendor/autoload.php';

$agent = new \sinri\ark\database\couchdb\ArkCouchDBAgent("47.97.46.4", 5984, "sinri", "20180817");

function show($name, $item)
{
    echo ">>>>>> " . $name . " : " . PHP_EOL;
    echo json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    echo "<<<<<< " . PHP_EOL;
}