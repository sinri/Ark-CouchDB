<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/12
 * Time: 11:58
 */

namespace sinri\ark\database\couchdb\test;


class TestFacade
{
    /**
     * @return \sinri\ark\database\couchdb\ArkCouchDBAgent
     */
    public static function getTestAgent()
    {
        $host = "";
        $port = 11111;
        $username = "";
        $password = "";

        if (file_exists(__DIR__ . '/config.php')) {
            require __DIR__ . '/config.php';
        }

        $agent = new \sinri\ark\database\couchdb\ArkCouchDBAgent($host, $port, $username, $password);
        return $agent;
    }

    /**
     * @param string $name
     * @param mixed $item
     */
    public static function show($name, $item)
    {
        echo ">>>>>> " . $name . " : " . PHP_EOL;
        echo json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        echo "<<<<<< " . PHP_EOL;
    }
}