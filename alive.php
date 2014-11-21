<?php
require('config/config.php');
require('classes/database.php');
require('classes/stats.php');

$stats = new Stats($config);

echo ($stats->alive()) ? 'true' : 'false';
