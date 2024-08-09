<?php
    session_start();
?>
<!Doctype html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>About</title>
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/about.css">
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>

    </head>
    <body>
    <noscript>Your browser does not support JavaScript!</noscript>
    <?php include './components/navbar.php'; ?>        <!-- Main content of the page, with text describing the goal of
            the website and a button which redirects to the login page -->
        <div class="container">
            <div class="description">
                <div class="website-title">
                    <span>The best website for personal finances</span>
                </div>
            </div>
            <div class="encourageText">
                <div>
                    <span class="strokeText">Controll </span>
                    <span>Your</span>
                </div>
                <div>
                    <span>Expenses Everywhere</span>
                </div>
                <div>
                    <span class="lowerText">We will help You take controll of your finances!</span>
                </div>
            </div>
            <div class="aboutButton">
                <button class="btnAbout" onclick="location.href = 'login.php'">Get Started</button>
            </div>
        </div>
    </body>
</html>