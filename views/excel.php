<?php
    $file="platform_infos.xls";
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$file");
?>

<h1>Platforms</h1>
<table class="table table-striped table-bordered">
    <tr>
        <th>Date</th>
        <th>Url</th>
        <th>Ip</th>
        <th>Lang</th>
        <th>Country</th>
        <th>Email</th>
        <th>CoreBundle version</th>
        <th>Workspaces</th>
        <th>Users</th>
        <th>Type</th>
        <th>Active</th>
    </tr>
    <?php
    foreach ($stats->getNonLocalHostStats() as $stat) {
        extract($stat);
        include('views/row.php');
    }
    ?>
</table>
<br>
<br>
<br>

<h1>localhost</h1>
<table class="table table-striped table-bordered">
    <tr>
        <th>Date</th>
        <th>Url</th>
        <th>Ip</th>
        <th>Lang</th>
        <th>Country</th>
        <th>Email</th>
        <th>CoreBundle version</th>
        <th>Workspaces</th>
        <th>Users</th>
        <th>Type</th>
        <th>Active</th>
    </tr>
    <?php
    foreach ($stats->getLocalHostStats() as $stat) {
        extract($stat);
        include('views/row.php');
    }
    ?>
</table>
