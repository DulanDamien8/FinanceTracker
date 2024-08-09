<?php
    session_start();
    require './components/login_validation.php';

    function sanitizeInput($conn, $var) {
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return mysqli_real_escape_string($conn, $var);
    }

    //function to check if the user exists in the database
    function checkIfUserExists($email, $password): bool
    {
        $conn = require './data/database.php';
        $sql = sprintf("SELECT * FROM users WHERE
                        Email = '%s'", sanitizeInput($conn, $email));
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        if (!empty($user)) {
            if (password_verify($password, $user['Password'])) {
                $_SESSION['uuid'] = $user['UUID'];
                $_SESSION['name'] = $user['Name'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //if access is already authorized and user is logged in we redirect to the homepage, else display login page
    if (isset($_SESSION['uuid'], $_COOKIE['authorized'])) {
        header("location:./index.php");
    } else {
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>Login</title>
        <link rel="stylesheet" href="./styles/login.css">
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/success_error.css"
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <noscript>Your browser does not support JavaScript!</noscript>
        <?php include './components/navbar.php'; ?>
        <!-- Div for showing the main content -->
        <div class="LoginSite">
            <?php
                //upon clicking the login button the post is validated from the form arguments
                if ($_POST && isset($_POST['mail'], $_POST['password'], $_POST['login'])) {
                    $check = true;
                    if (!validateEmail($_POST['mail'])) {

                        $error = "Email must be of likes \"name.name@provider.com\".
                        Double dots and use of more than once of the symbol \"@\" is prohibited.
                        Characters permitted in the first part of the email address:
                        !#$%\'*+-/?^\{|}~";

                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validatePassword($_POST['password'])) {

                        $error = "The password must be at least 8 characters long, 
                        must contain a letter and an number";

                        $errorArr[] = $error;
                        $check = false;
                    }
                    if ($check == true) {
                        if (checkIfUserExists($_POST['mail'], $_POST['password'])) {
                            displaySuccessMessage();
                            $optionsArr = array(
                                'expires' => 0,
                                'path' => '/~ducand/project',
                                'domain' => '',
                                'secure' => true,
                                'httponly' => true,
                                'samesite' => 'Strict'
                            );
                            setcookie('authorized', '1', $optionsArr);
                            header("refresh:2;url=./index.php");
                        } else {
                            $error = "Account not found!";
                            $errorArr[] = $error;
                            displayError($errorArr);
                        }
                    } else {
                        displayError($errorArr);
                    }
                }
            ?>
            <div class="LoginMain">
                <h2>Sign in  <i class="fa-solid fa-right-to-bracket fa-beat"></i></h2>
                <!-- Form for user login credentials -->
                <form action="./login.php" method="post" id="login-form">
                    <div class="LoginMail">
                        <label for="mail">Email
                            <input type="email" id="mail" name="mail"
                                   pattern="^(?![^\x22]+.*[^\x22]+\.\.)[a-zA-Z0-9 !\.#$%\&'*+\/=?^_`\{\|\}~\x22\-]*[a-zA-Z0-9\x22]+[@][a-zA-Z0-9\.\-]+$"
                                   autocomplete="on" required>
                        </label>
                    </div>
                    
                    <div class="LoginPass">
                        <label for="password">Password
                            <input type="password" id="password" name="password"
                                   minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                                   autocomplete="on" required>
                        </label>
                    </div>
                    <button id="login" type="submit" name="login" class="button1">
                        Sign in  <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
                </form>
                <button id="signupbtn" class="button2" onclick="location.href = 'register.php'">
                    Sign Up  <i class="fa-solid fa-user-plus"></i>
                  </button>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>