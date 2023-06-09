<?php

class dbPdo
{
    private static $pdo = null;
    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());

    }

    /**
     * @return static
     * @property null|PDO $conn
     */
    private static function initConnection()
    {
        $db = static::getInstance();
        $db->conn = new PDO("mysql:host=localhost;dbname=marriage_agency", "root", "");

        return $db;
    }

    /**
     * @return null|PDO
     */
    public static function getPdo()
    {

        $db = static::initConnection();

        return $db->conn;

    }
}