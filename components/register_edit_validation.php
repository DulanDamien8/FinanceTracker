<?php

    //list of functions for the validation of register form and edit account form

    $errorArr = array();

    //function to check if name does not start with a space, and contains letters, spaces
    //and characters ' and -
    function validateName($stringToCheck): bool
    {
        if (preg_match('/^(?! )[a-zA-Z \'-]*$/', $stringToCheck)) {
            return true;
        } else {
            return false;
        }
    }

    //function checking if post argument is not an empty one
    function validatePostNotEmpty($name): bool
    {
        if (!empty($name)) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if the email is within a permitted pattern, of likes "asd..asd"@asd
    //or asd.asd@asd, or asd@asd.com and so on
    function validateEmail($email): bool
    {
        if (preg_match('/^(?![^"]+.*[^"]+\.\.)[a-zA-Z0-9 !#"$%&\'*+-\/=?^_`{|}~]*[a-zA-Z0-9"]+@[a-zA-Z0-9.-]+$/', $email)) {
            return true;
        } else {
            return false;
        }
    }

    //function to validate if password is at leas 8 characters long and contains letters and numbers
    function validatePassword($password): bool
    {
        if (preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if password and password confirmation match
    function doPasswordsMatch($password, $passwordConfirmation): bool
    {
        if ($password === $passwordConfirmation) {
            return true;
        } else {
            return false;
        }
    }

    //funciton to display a success message
    function displaySuccessMessage($message): void
    {
        echo '
                    <div id="successMsg">
                        <b>'.$message.'</b>
                    </div>
                ';
    }

    //function to display an error message from the array of errors
    function displayError($error): void
    {
        echo '
              <div id="confirmedError">
                <b>The data which you have provided has errors!</b>
             ';
        if (is_array($error)) {
            foreach ($error as $value) {
                echo '<p>'.$value.'</p>';
            }
        }
        echo ' 
                </div>
             ';
    }
?>