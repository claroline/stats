<?php

require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);

if (!$stats->checkParameters(
        $_POST,
        array(
            'url',
            'version',
            'workspaces',
            'personal_workspaces',
            'users',
            'stats_type',
            'token'
        )
    )
) {
	echo 'Some parameters where missing'; 
	die();
}

$ip = isset($_POST['ip']) && !empty($_POST['ip']) ? $_POST['ip'] : '-';
$name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '-';
$lang = isset($_POST['lang']) && !empty($_POST['lang']) ? $_POST['lang'] : '-';
$country = isset($_POST['country']) && !empty($_POST['country']) ? $_POST['country'] : '-';
$email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : 'nomail@claroline-connect.net';

//if (!filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
//	echo "The ip {$_POST['ip']} is not valid";
//	die();
//}
//if (!filter_var($_POST['url'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
//	echo "The url {$_POST['url']} is not valid";
//	die();
//}

if (!preg_match('/^(http:\/\/)?localhost/', $_POST['url']) === 0) {
	echo 'localhost is not allowed as an url';
	die();
}

//if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
//	echo "The email {$_POST['email']} is not valid";
//	die();
//}

//if (!preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/', $_POST['version'])) {
//	echo "The version {$_POST['version']} is not valid";
//	die();
//}

if (!is_numeric($_POST['workspaces'])) {
	echo "The number of workspaces {$_POST['workspaces']} is not an integer";
	die();
}

if (!is_numeric($_POST['personal_workspaces'])) {
	echo "The number of personal_workspaces {$_POST['personal_workspaces']} is not an integer";
	die();
}

if (!is_numeric($_POST['users'])) {
	echo "The number of users {$_POST['users']} is not an integer";
	die();
}

if (!is_numeric($_POST['stats_type'])) {
	echo "The stats_type {$_POST['stats_type']} is not an integer";
	die();
}
	

$stats->insert(
	$ip,
	$name,
	'http://' . $_POST['url'],
	$lang,
	$country,
	$email,
	$_POST['version'],
	$_POST['workspaces'],
	$_POST['personal_workspaces'],
	$_POST['users'],
	$_POST['stats_type'],
	$_POST['token']
);

echo 'Data stored !';

