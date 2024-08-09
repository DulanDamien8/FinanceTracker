<?php
    session_start();
    require './components/register_edit_validation.php';

    $id = "";
    $name = "";
    $email = "";
    $uuid = $_SESSION['uuid'];

    //function to update the user account information in the database
    function updateUser($id, $name, $email, $password): void {
        $conn = require 'data/database.php';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET Name='$name', Email='$email', Password='$passwordHash' WHERE ID='$id'";
        $result = $conn->query($sql);
        if (!$result) {
            $error = $conn->error;
            $errorArr[] = $error;
            displayError($errorArr);
            $conn->close();
            exit();
        }
        $conn->close();

    }

    //if user is authorized then we get the name and email from the database and inject it to the input fields,
    //else we redirect to the login page
    if (!isset($_SESSION['uuid']) && $_COOKIE['authorized'] !== "1") {
        header("location: ./login.php");
    } else {
        $conn = require 'data/database.php';
        $sql = "SELECT * FROM users WHERE UUID='$uuid' LIMIT 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if (!$row) {
            header("location: ./login.php");
            exit();
        }
        $id = $row['ID'];
        $name = $row['Name'];
        $email = $row['Email'];

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>MyAccount</title>
        <link rel="stylesheet" href="./styles/myaccount.css">
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/success_error.css">
        <link rel="stylesheet" href="./styles/confirmation_popup.css">
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">var id =<?= $id ?>; </script>
        <script src="./javascript/myaccount.js" async></script>
    </head>
    <body>
        <noscript>Your browser does not support JavaScript!</noscript>
        <?php include './components/navbar.php'; ?>
        <?php
        //upon the save button click the post is validated
        if ($_POST && isset($_POST['name'], $_POST['email'], $_POST['password'],
                $_POST['password-confirmation'], $_POST['save-user-changes'])) {
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
            if ($_COOKIE['authorized'] !== "1") {
                $err = "Unauthorized access!";
                $errorArr[] = $error;
                displayError($errorArr);
                $check = false;
            }
            if ($check == true) {
                updateUser($id, $_POST['name'], $_POST['email'], $_POST['password']);
                displaySuccessMessage("Account updated!");
                $_SESSION['name'] = $_POST['name'];
                //after a successful edition and save we redirect to the homepage
                header("refresh:2;url=./index.php");
            } else {
                displayError($errorArr);
            }
        }
    ?>
        <!-- Div containing a form for the edition of the account details -->
        <div class="MyAccountMain">
            <h2>My account  <i class="fa-solid fa-user fa-beat"></i></h2>
            <form action="./myaccount.php" method="post" id="edit-account-form">
                <div class="UserName">
                    <label for="name">Name
                        <input type="text" id="name" name="name" pattern="[a-zA-z'\-][a-zA-Z '\-]*"
                               minlength="3" maxlength="30" autocomplete="on" value="<?php echo $name; ?>"
                               disabled="disabled" required>
                    </label>
                </div>
                <div class="UserEmail">
                    <label for="email">Email
                        <input type="email" id="email" name="email"
                               pattern="^(?![^\x22]+.*[^\x22]+\.\.)[a-zA-Z0-9 !\.#$%\&'*+\/=?^_`\{\|\}~\x22\-]*[a-zA-Z0-9\x22]+[@][a-zA-Z0-9\.\-]+$"
                               autocomplete="on" value="<?php echo $email; ?>"
                               disabled="disabled" required>
                    </label>
                </div>
                <div class="UserPassword">
                    <label for="password">Password
                        <input type="password" id="password" name="password" minlength="8"
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" autocomplete="on"
                               disabled="disabled" required>
                    </label>
                </div>
                <div class="UserPasswordConfirmation">
                    <label for="password-confirmation">Password Confirmation
                        <input type="password" id="password-confirmation" name="password-confirmation"
                               minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" autocomplete="on"
                               disabled="disabled" required>
                    </label>
                </div>
                <button id="save-user-changes" name="save-user-changes" class="SaveBtn" type="submit" disabled="disabled"> Save
                    <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </form>
            <!-- EditBtn will enable the edition of the data once there is a server with js -->
            <button id="enable-edit" name="enable-edit" class="EditBtn"> Edit
                <i class="fa-solid fa-pen-to-square"></i></button>
            <button id="deleteuserbtn" class="DeleteBtn" disabled="disabled"> Delete account
                <i class="fa-solid fa-trash"></i></button>
        </div>
            <!-- Confirmation which pops up after user clicks DeleteBtn -->
        <div class="DeleteUser" style="display: none;">
            <p>Are you sure you want to delete your account?</p>
            <div class="popup_buttons">
                <button id="confirm-delete" name="confirm-user-delete" class="ConfirmBtn" type="submit">Yes</button>
                <button id="cancel-delete" name="cancel-user-delete" class="CancelBtn" type="submit">Cancel</button>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
