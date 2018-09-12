<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/10
 * Time: 23:36
 */

use sinri\ark\database\couchdb\test\SinriTestDocument;
use sinri\ark\database\couchdb\test\TestFacade;

require_once __DIR__ . '/test-include.php';

$db = new \sinri\ark\database\couchdb\test\SinriTestDatabase();
$db->ensureDatabaseExists();
$docs = $db->findDocuments([], 5, 0);
TestFacade::show("docs", $docs);

//$db = (\sinri\ark\database\couchdb\entity\ArkCouchDBDatabaseEntity::openDatabaseAndGetEntity($agent, "sinri_test"));
$docs = $db->findDocuments(["a" => "AAA"], 5, 0);
TestFacade::show("docs", $docs);

$doc = new SinriTestDocument();
$doc->loadExistDocument("84e687380102596a1471f451ed035979");
TestFacade::show("doc", "" . $doc);

$doc = new SinriTestDocument();
$doc->modifyProperty("a", "VVV");
$doc->modifyProperty("b", "OOO");
$updated = $doc->update();
TestFacade::show("updated", $updated);

$db = (\sinri\ark\database\couchdb\entity\ArkCouchDBDatabaseEntity::openDatabaseAndGetEntity($agent, "sinri_test"));
$docs = $db->findDocuments(["a" => "VVV"], 5, 0);
TestFacade::show("docs", $docs);

$deleted = $doc->delete();
TestFacade::show('deleted', $deleted);

$db = (\sinri\ark\database\couchdb\entity\ArkCouchDBDatabaseEntity::openDatabaseAndGetEntity($agent, "sinri_test"));
$docs = $db->findDocuments(["a" => "VVV"], 5, 0);
TestFacade::show("docs", $docs);