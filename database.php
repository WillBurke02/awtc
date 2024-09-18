<?php
//Database Connection

$host = "localhost";
$dbname = "x3e94";
$username = "x3e94";
$password = "x3e94x3e94";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

 ?>
