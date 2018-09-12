# Ark-CouchDB
The CouchDB component for Ark 2

[CouchDB](http://couchdb.apache.org/) is a NoSQL database providing HTTP API to serve clients.
This library provided raw API client (with cURL) and entity class to cover basic use.
Now it is not fully accomplished, only base requirement considered.

## Agent

To connect to the CouchDB instance, you must establish an `ArkCouchDBAgent` class with connection arguments.

```php
$agent = new \sinri\ark\database\couchdb\ArkCouchDBAgent($host, $port, $username, $password);
``` 

Each API request is independent, so you can share this agent instance anywhere in your project.

## API Client

All CouchDB APIs are requested through HTTP by cURL.
For the request and response, two class defined, `ArkCouchDBRequest` and `ArkCouchDBResponse`.
The two classes are use by the extended classes of `ArkCouchDBAbstractApi`, which provided a method `callApi`.
This method receives a request instance and would output a response instance.

### Request

You should set request with those properties,

* $api; The sub URL for the API.
* $method; The constant defined for METHOD.
* $headers; The headers, as an associated array.
* $queries; The queries, as an associated array.
* $body; The body. If type is of a JSON, you can set it as an array or object.
* $bodyType; The constant defined for METHOD.

### Response

Response is simple, you can get the response HTTP code and headers, and the raw text and parsed json object (as array).

### Implemented API

Four kinds of API clients are provided,

* Server
* Database
* Document
* DesignDoc

You can check each PHPDoc for use and see the original doc online by reference tag.

## Object Entities

Alike PDO, two kinds of object entities designed.
One for database and one for documents.
You must extend them before use.