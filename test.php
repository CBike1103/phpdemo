<?php
use LinkORB\Component\Etcd\Client;

$client = new Client();
$client->set('/foo', 'fooValue');
// Set the ttl
$client->set('/foo', 'fooValue', 10);
// get key value
echo $client->get('/foo');