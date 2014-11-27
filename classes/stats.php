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
        }
    }

    /**
     * Insert into stats, no ned mysql_real_escape_string cause PDO prepare query
     */
    public function insert($ip, $url, $lang, $country, $email, $version, $workspaces, $users, $date = null)
    {
        if (!$date or ($date and !$this->validateDate($date))) {
            $date = date("Y-m-d H:i:s");
        }
        extract($this->array2utf8(get_defined_vars()));

        return $this->db->query(
            "INSERT INTO `stats` (
                `id`, `ip`, `url`, `lang`, `country`, `email`, `version`, `workspaces`, `users`, `date`
            ) VALUES (NULL, '$ip', '$url', '$lang', '$country', '$email', '$version', '$workspaces', '$users', '$date')"
        );
    }

    /**
     * Get stats entries
     */
    public function getStats()
    {
        if ($this->db) {
            return $this->db->query('SELECT * FROM `stats` ORDER BY `date` DESC LIMIT 500')->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Count field, as version, lang, country or month
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
        }
    }

    /**
     * Count field by month in a year
     */
    public function timed($field, $year = null)
    {
        $array = array();

        foreach ($this->countField($field, true, $year) as $item) {
            $array[$item[$field]][$item['month']] = $item['number'];
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
}
