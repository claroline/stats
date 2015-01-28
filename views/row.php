<tr>
    <td><?php $stats->show($date); ?></td>
    <td>
        <a href="<?php $stats->show($url); ?>" target="_blank">
            <?php $stats->show($url); ?>
        </a>
    </td>
    <td>
        <a href="http://ip-api.com/<?php $stats->show($ip); ?>" target="_blank">
            <?php $stats->show($ip); ?>
        </a>
    </td>
    <td class="text-uppercase"><?php $stats->show($lang); ?></td>
    <td class="text-capitalize"><?php $stats->show($country); ?></td>
    <td>
        <a href="mailto:<?php $stats->show($email); ?>" target="_blank">
            <?php $stats->show($email); ?>
        </a>
    </td>
    <td>
        <a href="https://github.com/claroline/CoreBundle/tree/<?php $stats->show($version); ?>" target="_blank">
            <?php $stats->show($version); ?>
        </a>
    </td>
    <td><?php $stats->show($workspaces); ?></td>
    <td><?php $stats->show($users); ?></td>
    <td><?php $stats->show($stats_type); ?></td>
    <td><?php $stats->show($active); ?></td>
</tr>
