<?php
require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);

$year = (isset($_GET['year']) and is_numeric($_GET['year'])) ? $_GET['year'] : date('Y');
$excel = isset($_GET['excel']);
$platformId = isset($_GET['platformId']) ? $_GET['platformId'] : null;
$isProd = isset($_GET['isProd']) ? intval($_GET['isProd']) : null;

if ($excel) {
    include('views/excel.php');
} else {
    //@TODO Sort by in the table and pagination
    include('views/layout.php');
}
