<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class databaseConnection
{
    public $conn;

    public function __construct()
    {
        try
        {
            $this->conn = new PDO("mysql:host=localhost;dbname=RS_IMG", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo 'Failed to connect: ' . $e->getMessage();
        }
    }
}

?>