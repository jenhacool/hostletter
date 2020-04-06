<?php

require_once 'init.php';
require_once __DIR__ . '/modules/servers/poc/libs/DB.php';
require_once __DIR__ . '/modules/servers/poc/libs/Email.php';
require_once __DIR__ . '/modules/servers/poc/libs/API.php';

if(!isset($_GET['server_id']) || !isset($_GET['username']) || !isset($_GET['password'])) {
    die();
}

$db = new DB();

$data = $db->getDataByServerId($_GET['server_id']);

if(empty($data)) {
    die();
}

$localServer = $db->getLocalServer($data->local_server_id);

if(empty($localServer)) {
    die();
}

$url = $localServer->secure == 'on' ? 'https://'.$localServer->hostname.':3020'.'/' : 'http://'.$localServer->hostname.':3020'.'/';
$token = $localServer->accesshash;

$api = new API($url, $token);
$server = $api->getServer($_GET['server_id']);

if(is_null($server)) {
    die('abc');
}

$email = new Email();
$email->wpPassword($data->client_id, $data->server_ip, $_GET['username'], $_GET['password']);

die();