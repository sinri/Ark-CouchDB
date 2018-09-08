<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/8
 * Time: 13:15
 */

namespace sinri\ark\database\couchdb\api;


use sinri\ark\database\couchdb\io\ArkCouchDBRequest;
use sinri\ark\database\couchdb\io\ArkCouchDBResponse;

class ArkCouchDBServerApi extends ArkCouchDBAbstractApi
{
    /**
     * Accessing the root of a CouchDB instance returns meta information about the instance.
     * The response is a JSON structure containing information about the server, including a welcome message and the version of the server.
     *
     *
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#api-server-root
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getCouchDBInstanceMetaInfo()
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/");
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Note: Changed in version 2.1.0: Because of how the scheduling replicator works, continuous replication jobs could be periodically stopped and then started later. When they are not running they will not appear in the _active_tasks endpoint
     *
     * List of running tasks, including the task type, name, status and process ID. The result is a JSON array of the currently running tasks, with each task being described with a single object. Depending on operation type set of response object fields might be different.
     *
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#active-tasks
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getActiveTasks()
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_active_tasks");
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Returns a list of all the databases in the CouchDB instance.
     *
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#all-dbs
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getAllDatabases()
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_all_dbs");
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Returns information of a list of the specified databases in the CouchDB instance. This enables you to request information about multiple databases in a single request, in place of multiple GET /{db} requests.
     *
     * The supported number of the specified databases in the list can be limited by modifying the max_db_number_for_dbs_info_req entry in configuration file. The default limit is 100.
     *
     * @since CouchDB 2.2
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#dbs-info
     * @param string[] $databases
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function batchQueryDatabaseInfo($databases)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_POST, "/_dbs_info");
        $request->setBodyAndType(["keys" => $databases], ArkCouchDBRequest::BODY_AS_JSON);
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * The state returned indicates the current node or cluster state, and is one of the following:
     *
     * cluster_disabled: The current node is completely unconfigured.
     * single_node_disabled: The current node is configured as a single (standalone) node ([cluster] n=1), but either does not have a server-level admin user defined, or does not have the standard system databases created. If the ensure_dbs_exist query parameter is specified, the list of databases provided overrides the default list of standard system databases.
     * single_node_enabled: The current node is configured as a single (standalone) node, has a server-level admin user defined, and has the ensure_dbs_exist list (explicit or default) of databases created.
     * cluster_enabled: The current node has [cluster] n > 1, is not bound to 127.0.0.1 and has a server-level admin user defined. However, the full set of standard system databases have not been created yet. If the ensure_dbs_exist query parameter is specified, the list of databases provided overrides the default list of standard system databases.
     * cluster_finished: The current node has [cluster] n > 1, is not bound to 127.0.0.1, has a server-level admin user defined and has the ensure_dbs_exist list (explicit or default) of databases created.
     */

    const CLUSTER_STATE_cluster_disabled = "cluster_disabled";
    const CLUSTER_STATE_single_node_disabled = "single_node_disabled";
    const CLUSTER_STATE_single_node_enabled = "single_node_enabled";
    const CLUSTER_STATE_cluster_enabled = "cluster_enabled";
    const CLUSTER_STATE_cluster_finished = "cluster_finished";

    /**
     * Returns the status of the node or cluster, per the cluster setup wizard.
     *
     * @since CouchDB 2.0
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#cluster-setup
     * @param string[] $databases List of system databases to ensure exist on the node/cluster. Defaults to ["_users","_replicator","_global_changes"].
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getClusterSetup($databases)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_cluster_setup");
        $request->setQueries([
            "ensure_dbs_exist" => $databases
        ]);
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Configure a node as a single (standalone) node, as part of a cluster, or finalise a cluster.
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#post--_cluster_setup
     * @param $object
     * @throws \Exception
     */
    public function postClusterSetup($object)
    {
        throw new \Exception("Not implemented yet");
    }

    /**
     * Returns a list of all database events in the CouchDB instance.
     *
     * feed (string)
     *  normal: Returns all historical DB changes, then closes the connection. Default.
     *  longpoll: Closes the connection after the first event.
     *  continuous: Send a line of JSON per event. Keeps the socket open until timeout.
     *  eventsource: Like, continuous, but sends the events in EventSource format.
     *
     * @since CouchDB 1.4
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#db-updates
     * @param string $feed normal, longpoll, continuous or eventsource
     * @param int $timeout Number of seconds until CouchDB closes the connection. Default is 60.
     * @param int|true $heartbeat Period in milliseconds after which an empty line is sent in the results. Only applicable for longpoll, continuous, and eventsource feeds. Overrides any timeout to keep the feed alive indefinitely. Default is 60000. May be true to use default value.
     * @param string $since Return only updates since the specified sequence ID. May be the string now to begin showing only new updates.
     * @throws \Exception
     */
    public function getDatabaseUpdates($feed, $timeout, $heartbeat, $since)
    {
        throw new \Exception("Not implemented yet");
    }

    /**
     * Displays the nodes that are part of the cluster as cluster_nodes. The field all_nodes displays all nodes this node knows about, including the ones that are part of the cluster. The endpoint is useful when setting up a cluster, see Node Management
     *
     * @since CouchDB 2.0
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getMemberShip()
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_membership");
        $response = $this->callApi($request);
        return $response;
    }

    // All replicate, scheduler, node API are not in plan
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#replicate
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#scheduler-jobs
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#scheduler-docs
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#node-node-name-stats
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#node-node-name-system
    // @see http://docs.couchdb.org/en/latest/api/server/common.html#node-node-name-restart

    /**
     * Confirms that the server is up, running, and ready to respond to requests. If maintenance_mode is true or nolb, the endpoint will return a 404 response.
     *
     * @since CouchDB 2.0
     * @see http://docs.couchdb.org/en/latest/api/server/common.html#up
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function isServerUp()
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_up");
        $response = $this->callApi($request);
        return $response;
    }

    /**
     * Requests one or more Universally Unique Identifiers (UUIDs) from the CouchDB instance. The response is a JSON object providing a list of UUIDs.
     *
     * @since CouchDB 2.0.0
     * @param int $count Number of UUIDs to return. Default is 1.
     * @return ArkCouchDBResponse
     * @throws \Exception
     */
    public function getUUIDs($count)
    {
        $request = new ArkCouchDBRequest(ArkCouchDBRequest::METHOD_GET, "/_uuids");
        $request->setQueries(["count" => $count]);
        $response = $this->callApi($request);
        return $response;
    }
}