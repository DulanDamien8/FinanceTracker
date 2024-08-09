<?php
    session_start();

    //function to sanitize the get argument
    function sanitizeInput($conn, $var) {
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return mysqli_real_escape_string($conn, $var);
    }

    //if the get method is set and access is authorized we delete the expense, else redirect to idex page
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!isset($_GET['id']) || $_COOKIE['authorized'] !== "1") {
            header("location: ./index.php");
            exit();
        } else {
            $conn = require 'data/database.php';
            $id = intval(sanitizeInput($conn, $_GET['id']));
            $sql = "DELETE FROM shopping WHERE ID='$id'";
            $result = $conn->query($sql);
            $conn->close();
            header("location: ./index.php");
        }
    }


