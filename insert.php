<?php

require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);

if ($stats->checkParameters(
        $_POST,
        array(
            'ip',
            'name',
            'url',
            'lang',
            'country',
            'email',
            'version',
            'workspaces',
            'users',
            'stats_type',
            'token'
        )
    ) &&
    filter_var($_POST['ip'], FILTER_VALIDATE_IP) &&
    filter_var($_POST['url'], FILTER_VALIDATE_URL) &&
    filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
    preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/', $_POST['version']) &&
    is_numeric($_POST['workspaces']) &&
    is_numeric($_POST['users']) &&
    is_numeric($_POST['stats_type'])) {

    $stats->insert(
        $_POST['ip'],
        $_POST['name'],
        $_POST['url'],
        $_POST['lang'],
        $_POST['country'],
        $_POST['email'],
        $_POST['version'],
        $_POST['workspaces'],
        $_POST['users'],
        $_POST['stats_type'],
        $_POST['token']
    );
}