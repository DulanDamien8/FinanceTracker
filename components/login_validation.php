<?php

    //list of function for login form validation

    $errorArr = array();

    //function to check if the email is within a required pattern
    //"asd.asd"@asd.com or asd.asd@asd or asd@asd.com and other combinations
    function validateEmail($email): bool
    {
        if (preg_match('/^(?![^"]+.*[^"]+\.\.)[a-zA-Z0-9 !#"$%&\'*+-\/=?^_`{|}~]*[a-zA-Z0-9"]+@[a-zA-Z0-9.-]+$/', $email)) {
            return true;
        } else {
            return false;
        }
    }

    //function to validate password, check if it is at least 8 chars long and contains letters and numbers
    function validatePassword($password): bool
    {
        if (preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            return true;
        } else {
            return false;
        }
    }

    //function to display the success message
    function displaySuccessMessage(): void
    {
        echo '
                <div id="successMsg">
                    <b>Welcome!</b>
                </div>
            ';
    }

    //function to display error message from error array
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