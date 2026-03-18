<?php

function connect()
{
    static $conn = null; // this stays across function calls

    if ($conn === null) {
        $servername = getenv('servername');
        $username = getenv('username');
        $password = getenv('password');
        $dbname = getenv('dbname');

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            
            // set the PDO error mode to exception
            // the other error modes either ignore errors or warn you but keep going
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    return $conn;
}