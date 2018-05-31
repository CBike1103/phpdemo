<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

use LinkORB\Component\Etcd\Client;

$client = new Client();
$client->set('/foo', 'fooValue');
// Set the ttl
// $client->set('/foo', 'fooValue', 10);
// get key value
echo $client->get('/foo');