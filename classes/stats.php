<?php

class Stats
{
    private $db;

    /**
     * Create a stat object
     */
    public function __construct($config)
    {
        $database = new DataBase($config);

        if ($database->connect()) {
            $this->db = $database;
        }
    }

    /**
     * Check if there is a conection to the database
     */
    public function alive()
    {
        if ($this->db) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * Insert into stats, no ned mysql_real_escape_string cause PDO prepare query
     */
    public function insert(
        $ip,
        $name,
        $url,
        $lang,
        $country,
        $email,
        $version,
        $workspaces,
        $personalWorkspaces,
        $users,
        $statsType,
        $token,
        $date = null
    )
    {
        if (!$date or ($date and !$this->validateDate($date))) {
            $date = date("Y-m-d H:i:s");
        }
        extract($this->array2utf8(get_defined_vars()));

        $platforms = $this->db->query(
            "SELECT *
             FROM `stats_platform`
             WHERE `url` = '$url'"
        )->fetchAll(PDO::FETCH_ASSOC);

        if (count($platforms) === 0) {
            $this->insertPlatform(
                $ip,
                $name,
                $url,
                $lang,
                $country,
                $email,
                $version,
                $workspaces,
                $personalWorkspaces,
                $users,
                $statsType,
                $token,
                $date
            );
        } else {
            $this->updatePlatform(
                $ip,
                $name,
                $url,
                $lang,
                $country,
                $email,
                $version,
                $workspaces,
                $personalWorkspaces,
                $users,
                $statsType,
                $token,
                $date
            );
        }

        return $this->db->query(
            "INSERT INTO `stats` (
                `id`, `ip`, `platform_name`, `url`, `lang`, `country`, `email`, `version`, `workspaces`, `personal_workspaces`, `users`, `stats_type`, `date`
            ) VALUES (NULL, '$ip', '$name', '$url', '$lang', '$country', '$email', '$version', '$workspaces', '$personalWorkspaces', '$users', '$statsType', '$date')"
        );
    }

    /**
     * Get stats entries
     */
    public function getStats()
    {
        if ($this->db) {

            return $this->db->query('SELECT * FROM `stats_platform` ORDER BY `date` DESC LIMIT 500')->fetchAll(PDO::FETCH_ASSOC);
        } else {

            return null;
        }
    }

    /**
     * Count field, as version, lang, country or month from 'stats' table
     */
    public function countField($field, $timed = false, $year = null)
    {
        $query = "SELECT `$field`, COUNT(*) AS `number` FROM `stats` GROUP BY `$field` ORDER BY `number` DESC";

        if ($field === 'month') {
            $query = "SELECT MONTHNAME(`date`) AS `month`, COUNT(*) AS `number`
                      FROM `stats` GROUP BY `month` ORDER BY `number` DESC";
        } else if ($timed) {
            $year = !$year ? date("Y") : $year;

            $query = "
                SELECT DATE_FORMAT(`date`, '%b') AS `month`, `$field`, COUNT(*) as `number` FROM `stats`
                WHERE YEAR(`date`) = '$year' GROUP BY `month`, `$field` ORDER BY `month`
            ";
        }

        if ($this->db) {

            return $this->array2utf8($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        } else {

            return null;
        }
    }

    /**
     * Count field, as version, lang, country or month from 'stats_platform' table
     */
    public function countPlatformField($field, $timed = false, $year = null)
    {
        $query = "
            SELECT `$field`, COUNT(*) AS `number`
            FROM `stats_platform`
            GROUP BY `$field`
            ORDER BY `number` DESC
        ";

        if ($field === 'month') {
            $query = "
                SELECT MONTHNAME(`date`) AS `month`, COUNT(*) AS `number`
                FROM `stats_platform`
                GROUP BY `month`
                ORDER BY `number` DESC
            ";
        } else if ($timed) {
            $year = !$year ? date("Y") : $year;

            $query = "
                SELECT DATE_FORMAT(`date`, '%b') AS `month`, `$field`, COUNT(*) as `number`
                FROM `stats_platform`
                WHERE YEAR(`date`) = '$year'
                GROUP BY `month`, `$field`
                ORDER BY `month`
            ";
        }

        if ($this->db) {

            return $this->array2utf8($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        } else {

            return null;
        }
    }

    public function countNbUpdatedPlaforms()
    {
        $nbUpdated = 0;

        if ($this->db) {
            $query = "
                SELECT COUNT(DISTINCT `url`) as `total`
                FROM `stats`
                WHERE `stats_type` = 3
                AND `url` IN (
                    SELECT `url`
                    FROM `stats_platform`
                )
            ";
            $result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
            
            if (isset($result['total']) && is_numeric($result['total'])) {

                $nbUpdated = $result['total'];
            }
        }

        return $nbUpdated;
    }

    public function computeSumByDate($field, $year = null)
    {
        $year = !$year ? date("Y") : $year;

        $query = "
            SELECT DATE_FORMAT(`date`, '%b') AS `month`, SUM(`$field`) as `number`
            FROM `stats` s
            WHERE YEAR(s.`date`) = '$year'
            AND NOT EXISTS (
                SELECT *
                FROM `stats` ss
                WHERE s.`url` = ss.`url`
                AND YEAR(ss.`date`) = '$year'
                AND MONTH(ss.`date`) = MONTH(s.`date`)
                AND ss.`date` > s.`date`
             )
            GROUP BY `month`
            ORDER BY `month`
        ";

        if ($this->db) {

            return $this->array2utf8($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        }  else {

            return null;
        }
    }

    public function countNbFieldByDate($field, $year = null)
    {
        $year = !$year ? date("Y") : $year;

        $query = "
            SELECT DATE_FORMAT(`date`, '%b') AS `month`, `$field`, COUNT(DISTINCT `url`) as `number`
            FROM `stats`
            WHERE YEAR(`date`) = '$year'
            GROUP BY `month`, `$field`
            ORDER BY `month`
        ";

        if ($this->db) {

            return $this->array2utf8($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        } else {

            return null;
        }
    }

    public function countNbPlatformsByDate($year = null)
    {
        $year = !$year ? date("Y") : $year;

        $query = "
            SELECT DATE_FORMAT(`date`, '%b') AS `month`, COUNT(DISTINCT `url`) as `number`
            FROM `stats`
            WHERE YEAR(`date`) = '$year'
            GROUP BY `month`
            ORDER BY `month`
        ";

        if ($this->db) {

            return $this->array2utf8($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        } else {

            return null;
        }
    }

    /**
     * Count field by month in a year
     */
    public function timed($field, $year = null)
    {
        $array = array();

        switch ($field) {

            case 'users':
            case 'workspaces':

                foreach ($this->computeSumByDate($field, $year) as $item) {
                    $array[$field][$item['month']] = $item['number'];
                }
                break;
            case 'platforms':

                foreach ($this->countNbPlatformsByDate($year) as $item) {
                    $array[$field][$item['month']] = $item['number'];
                }
                break;
            case 'country':
            case 'version':

                foreach ($this->countNbFieldByDate($field, $year) as $item) {
                    $array[$item[$field]][$item['month']] = $item['number'];
                }
                break;
            default:

                foreach ($this->countField($field, true, $year) as $item) {
                    $array[$item[$field]][$item['month']] = $item['number'];
                }
        }

        uasort($array, array('Stats', 'timedSort'));

        return $this->lessen($array);
    }

    /**
     * Lessen an array in the first 5 elements and put the others in an 'other' category
     */
    public function lessen($items)
    {
        $array = array();
        $index = 0;

        foreach ($items as $key => $item) {
            if ($index < 5) {
                $array[$key] = $item;
            } else {
                foreach ($item as $subKey => $subItem) {
                    if (!isset($array['others'][$subKey])) {
                        $array['others'][$subKey] = 0;
                    }
                    $array['others'][$subKey] += $subItem;
                }
            }

            $index++;
        }

        return $array;
    }

    /**
     * Sort timed queries
     */
    public function timedSort($a,$b)
    {
        if (array_sum($a) == array_sum($b)) {
            return 0;
        }

        return (array_sum($a) > array_sum($b)) ? -1 : 1;
    }

    /**
     * Get total number of entries
     */
    public function total()
    {
        if ($this->db) {

            return $this->db->query('SELECT count(*) AS `total` FROM `stats`')->fetch(PDO::FETCH_ASSOC)['total'];
        }

        return 0;
    }

    /**
     * Get total number of entries
     */
    public function platformTotal()
    {
        $total = 1;

        if ($this->db) {
            $query = '
                SELECT count(*) AS `total`
                FROM `stats_platform`
            ';
            $result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);

            if (isset($result['total']) && is_numeric($result['total'])) {

                $total = $result['total'];
            }
        }

        return $total;
    }

    /**
     * Echo with html entitites
     */
    public function show($value)
    {
        echo htmlEntities($this->string2utf8($value));
    }

    /**
     * Check if parameters are in an array and are not empty
     */
    public function checkParameters($array, $args)
    {
        foreach ($args as $arg) {
            if (!(isset($array[$arg]) && $array[$arg] !== '')) {

                return false;
            }
        }

        return true;
    }

    /**
     * Check if a date in an string is valid
     */
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $dateTime = DateTime::createFromFormat($format, $date);

        return $dateTime && $dateTime->format($format) == $date;
    }

    public function checkActiveRegisteredPlatforms()
    {
        if ($this->db) {
            $query = '
                SELECT *
                FROM `stats_platform`
                WHERE `active` = 1
            ';
            $results = $this->array2utf8(
                $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC)
            );

            foreach ($results as $result) {
                $token = $result['token'];
                $url = $result['url'];
                $updateUrl = $url .
                    '/admin/parameters/send/datas/token/' .
                    $token;

                $curl = curl_init($updateUrl);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($curl);
            }
        }
    }

    public function deactivatePlatform($id)
    {
        if ($this->db) {
            extract($this->array2utf8(get_defined_vars()));

            $this->db->query(
                "UPDATE `stats_platform`
                 SET `active` = '0'
                 WHERE `id` = $id"
            );
        }
    }

    public function deletePlatform($id)
    {
        if ($this->db) {
            extract($this->array2utf8(get_defined_vars()));

            $this->db->query(
                "DELETE FROM `stats_platform`
                 WHERE `id` = $id"
            );
        }
    }

    /**
     * Array to utf8
     */
    private function array2utf8($array)
    {
        array_walk_recursive(
            $array,
            function (&$item) {
                $item = $this->string2utf8($item);
            }
        );

        return $array;
    }

    /**
     * String to uf8
     */
    private function string2utf8($string)
    {
        if (!mb_detect_encoding($string, 'utf-8', true)) {
            $string = utf8_encode($string);
        }

        return $string;
    }

    private function insertPlatform(
        $ip,
        $name,
        $url,
        $lang,
        $country,
        $email,
        $version,
        $workspaces,
        $personalWorkspaces,
        $users,
        $statsType,
        $token,
        $date
    )
    {
        if ($this->db) {
            extract($this->array2utf8(get_defined_vars()));

            $this->db->query(
                "INSERT INTO `stats_platform` (
                    `id`, `ip`, `platform_name`, `url`, `lang`, `country`, `email`, `version`, `workspaces`, `personal_workspaces`,`users`, `stats_type`, `token`, `active`, `date`
                ) VALUES (NULL, '$ip', '$name', '$url', '$lang', '$country', '$email', '$version', '$workspaces', '$personalWorkspaces', '$users', '$statsType', '$token', '1','$date')"
            );
        }
    }

    private function updatePlatform(
        $ip,
        $name,
        $url,
        $lang,
        $country,
        $email,
        $version,
        $workspaces,
        $personalWorkspaces,
        $users,
        $statsType,
        $token,
        $date
    )
    {
        if ($this->db) {
            extract($this->array2utf8(get_defined_vars()));

            $this->db->query(
                "UPDATE `stats_platform`
                 SET `ip` = '$ip',
                     `platform_name` = '$name',
                     `lang` = '$lang',
                     `country` = '$country',
                     `email` = '$email',
                     `version` = '$version',
                     `workspaces` = '$workspaces',
                     `personal_workspaces` = '$personalWorkspaces',
                     `users` = '$users',
                     `stats_type` = '$statsType',
                     `token` = '$token',
                     `active` = '1',
                     `date` = '$date'
                 WHERE `url` = '$url'"
            );
        }
    }
}
