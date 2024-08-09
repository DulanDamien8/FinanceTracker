<?php
    session_start();
    global $categoryArr, $accountArr;
    require './components/add_edit_expense_validation.php';

    //function to add a new expense to the database, we get the session uuid, create a db connection
    //and then execute the query to insert a new expense to the databse with truncation of the amount
    //to 2 decimal places
    function addExpense($name, $amount, $category, $account, $date): void
    {
        $conn = require './data/database.php';
        $uuid = $_SESSION['uuid'];
        $sql = "INSERT INTO shopping (UserID, Name, Amount, Type, Payment, Date) VALUES (?, ?, TRUNCATE(?, 2), ?, ?, ?)";
        $stmt = $conn->stmt_init();
        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $conn->error);
        }
        $stmt->bind_param("ssdsss", $uuid, $name, $amount, $category, $account, $date);
        try {
            $stmt->execute();
            displaySuccessMessage("Expense added!");
            header("refresh:2;url=./index.php");
        } catch (mysqli_sql_exception $e) {
                $errorArr[] = "Error adding expense.";
                displayError($errorArr);
        }
    }

    //display contents of the page if access is authorized and session uuid has been set
    if ($_COOKIE['authorized'] === "1" && isset($_SESSION['uuid'])) {

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>Add Expense</title>
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/addandeditexpense.css">
        <link rel="stylesheet" href="./styles/success_error.css">
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
    </head>
    <body>
    <noscript>Your browser does not support JavaScript!</noscript>
    <?php include './components/navbar.php'; ?>        <!-- Main container div for the entire form -->
        <div id="expensetable">
            <?php
                //if the button to add expense is clicked the post is checked and validated
                if ($_POST && isset($_POST['expense-name'], $_POST['amount'], $_POST['category'],
                    $_POST['account'], $_POST['date'], $_POST['add-expense'])) {
                    $check = true;
                    if (!validateName($_POST['expense-name'])) {
                        $error = "Name needs to start with and can contain letters, numbers and characters ' and -";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validateAmout($_POST['amount'])) {
                        $error = "Amount cannot be negative or bigger than 1,000,000";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validateDate($_POST['date'])) {
                        $error = "Date is in wrong format";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validateDateBetween($_POST['date'])) {
                        $error = "Date must be between 2023-01-01 and 2033-01-01";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!checkCategoryOrAccount($_POST['category'], $categoryArr)) {
                        $error = "Category is not from a given list";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!checkCategoryOrAccount($_POST['account'], $accountArr)) {
                        $error = "Account is not from a given list";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if (!validatePostNotEmpty($_POST['expense-name']) || !validatePostNotEmpty($_POST['amount']) ||
                        !validatePostNotEmpty($_POST['date'])) {
                        $error = "Name, amount, and date fields must not be empty";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if ($_COOKIE['authorized'] !== "1") {
                        $error = "Unauthorized access!";
                        $errorArr[] = $error;
                        $check = false;
                    }
                    if ($check == true) {
                        addExpense($_POST['expense-name'], $_POST['amount'],
                            $_POST['category'], $_POST['account'], $_POST['date']);

                    } else {
                        displayError($errorArr);
                    }
                }
            ?>
            <div class="ex-form">
                <!-- Title of the form -->
                <h2>Add Expense  <i class="fa-solid fa-money-check-dollar fa-bounce"></i></h2>
                <!-- Form containing different inputs spread into separate divs for the ease of styling -->
                <!-- Inputs include the name, amount, category, type of payment and date  -->
                <form action="./addexpense.php" method="post" id="add-expense-form">
                    <div class="ex-name">
                        <label for="expense-name">Name:
                            <input type="text" id="expense-name" name="expense-name"
                                   pattern="[a-zA-z'\-][a-zA-Z0-9 '\-]*" minlength="3" maxlength="30"
                                    autocomplete="on" required>
                        </label>
                    </div>
                    <div class="ex-amount">
                        <label for="amount">Amount:
                            <input type="number" id="amount" name="amount" min="0.00" step="0.01"
                                   autocomplete="on" required>
                        </label>
                    </div>
                    <div class="ex-category">
                        <label for="category">Expense type:
                            <select id="category" name="category">
                                <option value="" selected>Select category</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Food">Food</option>
                                <option value="Transport">Transport</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Subscription">Subscription</option>
                                <option value="Rent">Rent</option>
                                <option value="Learning">Learning</option>
                            </select>
                        </label>
                    </div>
                    <div class="ex-account">
                        <label for="account">Paid with:
                            <select id="account" name="account">
                                <option value="" selected>Select payment type</option>
                                <option value="Mastercard">Mastercard</option>
                                <option value="Visa">Visa</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </label>
                    </div>
                    <div class="ex-date">
                        <label for="date">Date:
                            <input type="date" id="date" name="date" min="2023-01-01" max="2033-01-01" autocomplete="on" required>
                        </label>
                    </div>
                    <button id="add-expense" class="add-expense-button" type="submit" name="add-expense">
                        Add  <i class="fa-solid fa-plus"></i></button>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
        //if there is no authorization we redirect to the login page
} else {
    header("location:./login.php");
}
?>