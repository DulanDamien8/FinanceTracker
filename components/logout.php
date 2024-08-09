<?php
//    destroying the session and removing the cookie for authorization, then redirect to login page
    session_start();
    session_unset();
    session_destroy();
    $optionsArr = array(
        'expires' => time()-3600,
        'path' => '/~ducand/project',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    );
    setcookie('authorized', '1', $optionsArr);
    header("Location:../login.php");
?>
