<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claroline stats</title>
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/stats.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="assets/js/canvasjs.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <div id="version" style="height: 230px; width: 100%"></div>
            </div>
            <div class="col-sm-3">
                <div id="country" style="height: 230px; width: 100%"></div>
            </div>
            <div class="col-sm-3">
                <div id="lang" style="height: 230px; width: 100%"></div>
            </div>
            <div class="col-sm-3">
                <div id="month" style="height: 230px; width: 100%"></div>
            </div>
        </div>
        <hr>
        <div class="input-group">
            <span class="input-group-addon">Year</span>
            <select class="form-control" id="year">
                <?php for ($i = date('Y') ; $i >= date('Y') - 10 ; $i--) { ?>
                    <option <?php if ($year == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="versionTimed" style="height: 250px; width: 100%"></div>
            </div>
            <div class="col-sm-6">
                <div id="countryTimed" style="height: 250px; width: 100%"></div>
            </div>
        </div>
    </div>
    <br>
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
        </tr>
        <?php
        foreach ($stats->getStats() as $stat) {
            extract($stat);
            include('views/row.php');
        }
        ?>
    </table>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/stats.js"></script>
    <script type="text/javascript">
        var version = <?php echo json_encode($stats->countField('version')); ?>;
        var country = <?php echo json_encode($stats->countField('country')); ?>;
        var lang = <?php echo json_encode($stats->countField('lang')); ?>;
        var month = <?php echo json_encode($stats->countField('month')); ?>;
        var total = <?php echo $stats->total(); ?>;
        var year = <?php echo $year; ?>;

        var versionTimed = <?php echo json_encode($stats->timed('version', $year)); ?>;
        var countryTimed = <?php echo json_encode($stats->timed('country', $year)); ?>;

        window.onload = function () {
            new CanvasJS.Chart('version', new stat('version', 'Core Version').doughnut(version, total)).render();
            new CanvasJS.Chart('country', new stat('country', 'Countries').doughnut(country, total)).render();
            new CanvasJS.Chart('lang', new stat('lang', 'Languages').doughnut(lang, total)).render();
            new CanvasJS.Chart('month', new stat('month', 'Installed/Updated').doughnut(month, total)).render();

            new CanvasJS.Chart(
                'versionTimed', new stat('versionTimed', 'Core Versions ' + year).spline(versionTimed)
            ).render();
            new CanvasJS.Chart(
                'countryTimed', new stat('countryTimed', 'Countries ' + year).spline(countryTimed)
            ).render();
        }

        $('#year').on('change', function () {
            document.location.href = 'index.php?year=' + this.value;
        });
    </script>
</body>
</html>
