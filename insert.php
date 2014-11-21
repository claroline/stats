<?php
require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);


/*
// TEST
$_POST['ip'] = '192.168.1.1';
$_POST['url'] = 'http://claroline.net';
$_POST['lang'] = 'EN';
$_POST['country'] = 'Belgium';
$_POST['email'] = 'info@claroline.net';
$_POST['version'] = '2.6.0';
$_POST['workspaces'] = '0';
$_POST['users'] = '0';
$_POST['date'] = '2014-09-17 07:24:43';
*/

if (
    $stats->checkParameters(
        $_POST, array('ip', 'url', 'lang', 'country', 'email', 'version', 'workspaces', 'users', 'date')
    ) AND
    filter_var($_POST['ip'], FILTER_VALIDATE_IP) AND
    filter_var($_POST['url'], FILTER_VALIDATE_URL) AND
    filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) AND
    preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/', $_POST['version']) AND
    is_numeric($_POST['workspaces']) AND
    is_numeric($_POST['users']) AND
    $stats->validateDate($_POST['date']) AND
    $stats->insert(
        $_POST['ip'],
        $_POST['url'],
        $_POST['lang'],
        $_POST['country'],
        $_POST['email'],
        $_POST['version'],
        $_POST['workspaces'],
        $_POST['users'],
        $_POST['date']
    )->errorCode()
) {
    echo 'true';
} else {
    echo 'false';
}
