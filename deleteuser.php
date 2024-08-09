<?php
    session_start();

    //function to sanitize the get method argument
    function sanitizeInput($conn, $var) {
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return mysqli_real_escape_string($conn, $var);
    }

    //if get method is set and access is authorized we delete all the records from the database
    //for the logged in user and display the goodbye message and logout the user, else redirect to home page
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!isset($_GET['id']) || $_COOKIE['authorized'] !== "1") {
            header("location: ./index.php");
            exit();
        } else {
            $uuid = $_SESSION['uuid'];
            $conn = require 'data/database.php';
            $id = intval(sanitizeInput($conn, $_GET['id']));
            $sql = "DELETE FROM income WHERE UserID='$uuid'";
            $result2 = $conn->query($sql);
            $sql2 = "DELETE FROM shopping WHERE UserID='$uuid'";
            $result2 = $conn->query($sql2);
            $sql3 = "DELETE FROM users WHERE ID='$id'";
            $result = $conn->query($sql3);
            $conn->close();
?>
            <!DOCTYPE html>
            <html lang="en-US">
                <head>
                    <meta charset="utf-8">
                    <meta name="author" content="Marek Kopania">
                    <meta name="author" content="Mattias Nomm">
                    <meta name="author" content="Dulan Damien Candauda Arachchiege">
                    <title>Goodbye</title>
                    <link rel="stylesheet" href="./styles/navbar.css">
                    <link rel="stylesheet" href="./styles/success_error.css">
                    <!-- The script below is for linking FontAwesome icon library to the page -->
                    <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
                </head>
            <body>
                <noscript>Your browser does not support JavaScript!</noscript>
                <?php include './components/navbar.php'; ?>
                <div id="successMsg">
                    <b>Sad to see you go.</b>
                    <b>Goodbye!</b>
                </div>
            </body>
            </html>
<?php
            header("refresh: 5;url=./components/logout.php");
        }
    }
?>
