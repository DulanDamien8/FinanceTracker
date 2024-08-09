<?php

    //list of functions whihc validate the addexpense and editexpense forms

    //arrays for storing errors, for acceptable values from select options
    $errorArr = array();

    $categoryArr = array("", "Utilities", "Food", "Transport", "Shopping", "Entertainment", "Subscription", "Rent", "Learning");

    $accountArr = array("", "Mastercard", "Visa", "Cash");

    //function to validate name, check if it does not start with a space and contains only letters
    //numbers and symbols ' and -
    function validateName($stringToCheck): bool
    {
        if (preg_match('/^(?! )[a-zA-Z0-9 \'-]*$/', $stringToCheck)) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if the amount is not smaller or equal to 0
    function validateAmout($amount): bool
    {
        if ($amount > 0 && $amount < 1000000) {
            return true;
        } else {
            return false;
        }
    }

    //function checking if post variables are not an empty name
    function validatePostNotEmpty($name): bool
    {
        if (!empty($name)) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if the date is an existing one, and it does not contain
    //float values in the year month day fields
    function validateDate($date): bool
    {
        if (false === date_parse($date)) {
            return false;
        } else if (false === strtotime($date)) {
            return false;
        } else {
            $dateString = explode('-', $date);
            $dateFin = new DateTime();
            $dateFin->setDate(intval($dateString[0]), intval($dateString[1]), intval($dateString[2]));
            $result = $dateFin->format('Y-m-d');
            if ($date != $result) {
                return false;
            } else {
                return true;
            }
        }
    }

    //function to check weather the date is within the range
    //between 2023-01-01 and 2033-01-01
    function validateDateBetween($date): bool
    {
        $start_ts = strtotime('2023-01-01');
        $end_ts = strtotime('2033-01-01');
        $date_ts = strtotime($date);

        // Check that user date is between start & end
        if (($date_ts >= $start_ts) && ($date_ts <= $end_ts)) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if salutation is within permitted array
    function checkCategoryOrAccount($category, $arrCheck): bool
    {
        if (in_array($category, $arrCheck)) {
            return true;
        } else {
            return false;
        }
    }

    //function to display message on success
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
