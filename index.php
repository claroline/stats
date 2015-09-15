<?php
require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);

$year = (isset($_GET['year']) and is_numeric($_GET['year'])) ? $_GET['year'] : date('Y');
$excel = isset($_GET['excel']);

if ($excel) {
    include('views/excel.php');
} else {
    //@TODO Sort by in the table and pagination
    include('views/layout.php');
}
