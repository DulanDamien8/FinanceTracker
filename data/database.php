<?php
    $host = "anysql.itcollege.ee";
    $user = "ICS0008_WT_15";
    $password = "9165fc8ff869";
    $dbName = "ICS0008_15";

    $conn = new mysqli($host, $user, $password, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
