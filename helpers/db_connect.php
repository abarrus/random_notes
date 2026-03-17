<?php

function connect()
{
  $servername = getenv('servername');
  $username = getenv('username');
  $password = getenv('password');
  $dbname = getenv('dbname');

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
}