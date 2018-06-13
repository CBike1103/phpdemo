<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

use LinkORB\Component\Etcd\Client;

$cache_path = '/tmp/confcache';

function cache_set($key, $val) {
    global $cache_path;
    echo "set key: $key";
    $val = var_export($val, true);
    // HHVM fails at __set_state, so just use object cast for now
    $val = str_replace('stdClass::__set_state', '(object)', $val);
    // Write to temp file first to ensure atomicity
    $tmp = $cache_path.$key."." . uniqid('', true) . '.tmp';
    file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
    rename($tmp, "$cache_pathe$key");
}

function get_key($key) {
    global $cache_path;
    
    if(!substr($key, 0, 1) === "/") {
        throw new Exception('key must start with /');
    }

    @include "$cache_path$key";

    if(isset($val)) {
        return $val;
    }

    $client = new Client('http://127.0.0.1:2379', 'username', 'password');
    $val = $client->get($key);
    cache_set($key, $val);

    return $val;
}


$client = new Client('http://127.0.0.1:2379', 'username', 'password');
// $client->set('/foo', 'fooValue');
// Set the ttl
// $client->set('/foo', 'fooValue', 10);

echo get_key('/foo');