<?php

    session_start();
    require './components/register_edit_validation.php';

    //function which generates the unique identifier for the user, we do this to avoid collisions
    function uuidv4(): string {
        try {
            $data = random_bytes(16);
        } catch (Exception $e) {
            die("Cannot create UUID. " . $e->getMessage() . " - " . $e->getCode());
        }
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    //function to save a new user to the database
    function registerUser($name, $email, $password): void
    {
        $conn = require './data/database.php';

        $uuid = uuidv4();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (UUID, Name, Email, Password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->stmt_init();
        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $conn->error);
        }
        $stmt->bind_param("ssss", $uuid, $name, $email, $passwordHash);
        try {
            $stmt->execute();
            $stmt->close();
            $conn->close();
            createIncome($uuid);
            displaySuccessMessage("Welcome");
            header("refresh:3;url=./login.php");
        } catch (mysqli_sql_exception $e) {
            if ($conn->errno === 1062) {
                $errorArr[] = "Name or email are already taken.";
                displayError($errorArr);
            }
        }
    }

    //function which when the new user is created a new income of value 0.00 is also created in another
    //table for the user
    function createIncome($uuid): void {
        $conn = require './data/database.php';
        $amount = 0.00;
        $sql = "INSERT INTO income (UserID, Amount) VALUES (?, ?)";
        $stmt = $conn->stmt_init();
        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $conn->error);
        }
        $stmt->bind_param("sd", $uuid, $amount);
        try {
            $stmt->execute();
            $stmt->close();
            $conn->close();
        } catch (mysqli_sql_exception $e) {
            if ($conn->errno === 1062) {
                $errorArr[] = "Cannot create income.";
                displayError($errorArr);
            }
        }
    }

    //if user is already logged in we redirect to the home page, else we display the register page
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
        <title>Register</title>
        <link rel="stylesheet" href="./styles/register.css">
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/success_error.css"
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
        <noscript>Your browser does not support JavaScript!</noscript>
    </head>
    <body>
    <?php include './components/navbar.php'; ?>
        <div class="RegisterPage">
            <?php
                //upon clicking the sign-up button we validate the post and arguments from form
                if ($_POST && isset($_POST['name'], $_POST['email'], $_POST['password'],
                    $_POST['password-confirmation'], $_POST['sign-up-user'])) {
                    $check = true;
                    if (!validateEmail($_POST['email'])) {

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
                    if (!validateName($_POST['name'])) {
                        $error = "The names must begin with and can have letters, spaces and symbols ' and -";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validatePostNotEmpty($_POST['name']) || !validatePostNotEmpty($_POST['email']) ||
                        !validatePostNotEmpty($_POST['password']) || !validatePostNotEmpty($_POST['password-confirmation'])) {
                        $error = "All fields must be filled";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!doPasswordsMatch($_POST['password'], $_POST['password-confirmation'])) {
                        $error = "Password and password confirmation must match";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if ($check == true) {
                        registerUser($_POST['name'], $_POST['email'], $_POST['password']);
                    } else {
                        displayError($errorArr);
                    }
                }
            ?>
            <div class="Main">
                <h2>Register  <i class="fa-solid fa-user-plus fa-beat"></i></h2>
                <!-- Form for user details used for the creation of a new account -->
                <form action="./register.php" method="post" id="register-form">
                    <div class="Name">
                        <label for="name">Name
                        <input type="text" id="name" name="name" pattern="[a-zA-z'\-][a-zA-Z '\-]*" minlength="3" maxlength="30" autocomplete="on" required>
                        </label>
                    </div>
                    <div class="Mail">
                        <label for="email">Email
                        <input type="email" id="email" name="email" pattern="^(?![^\x22]+.*[^\x22]+\.\.)[a-zA-Z0-9 !\.#$%\&'*+\/=?^_`\{\|\}~\x22\-]*[a-zA-Z0-9\x22]+[@][a-zA-Z0-9\.\-]+$" autocomplete="on" required>
                        </label>
                    </div>
                    <div class="Password">
                        <label for="password">Password
                        <input type="password" id="password" name="password" minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" autocomplete="on" required>
                        </label>
                    </div>
                    <div class="ConfirmationPassword">
                        <label for="password-confirmation">Password Confirmation
                        <input type="password" id="password-confirmation" name="password-confirmation" minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" autocomplete="on" required>
                        </label>
                    </div>
                    <button id="sign-up-user" class="button" name="sign-up-user" type="submit">
                        Sign up  <i class="fa-solid fa-user-plus"></i>
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>