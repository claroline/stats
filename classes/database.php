<?php

class DataBase
{
    private $config;
    private $connect;

    /**
     * Create a database conection
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Connect to a database
     */
    public function connect()
    {
        try {
            $this->connect = new PDO($this->config['dsn'], $this->config['user'], $this->config['password']);
            $this->connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Execute a query in the database
     */
    public function query($sql, $execute = false)
    {
        if ($execute) {
            return $this->connect->query($sql);
        }

        $query = $this->connect->prepare($sql);
        $query->execute();

        return $query;
    }
}



