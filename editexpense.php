<?php
    session_start();
    global $categoryArr, $accountArr;
    require './components/add_edit_expense_validation.php';

    $id = "";
    $name = "";
    $amount = "";
    $category = "";
    $type = "";
    $date = "";

    //function to sanitize the get argument
    function sanitizeInput($conn, $var) {
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return mysqli_real_escape_string($conn, $var);
    }

    //function to update the expense with new properties
    function updateExpense($id, $name, $amount, $category, $type, $date): void {
        $conn = require 'data/database.php';
        $sql = "UPDATE shopping SET Name='$name', Amount=TRUNCATE('$amount', 2), 
                    Type='$category', Payment='$type', Date='$date' WHERE ID='$id'";
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

    //if get method is set we update the expense information, else redirect to the home page
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!isset($_GET['id'])) {
            header("location: ./index.php");
            exit();
        } else {
            $conn = require 'data/database.php';
            $id = intval(sanitizeInput($conn, $_GET['id']));
            $sql = "SELECT * FROM shopping WHERE ID='$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if (!$row) {
                header("location: ./index.php");
                exit();
            }
            $name = $row['Name'];
            $amount = $row['Amount'];
            $category = $row['Type'];
            $type = $row['Payment'];
            $date = $row['Date'];
            $conn->close();
        }
    }

    //if the user is authorized we display the content of the page else redirect to the homepage
    if ($_COOKIE['authorized'] === "1" && isset($_SESSION['uuid'])) {

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>Edit Expense</title>
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/addandeditexpense.css">
        <link rel="stylesheet" href="./styles/success_error.css">
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">var category = "<?= $category ?>"; var type = "<?= $type ?>";</script>
        <script src="./javascript/editexpense.js" async></script>
    </head>
    <body>
        <noscript>Your browser does not support JavaScript!</noscript>
        <?php include './components/navbar.php'; ?>        <!-- Main container div for the entire form -->
        <div id="expensetable">
            <?php
                //initialization of saving of edition of an expense upon clicking the save button
                //with validation of the form
                if ($_POST && isset($_POST['expense-name'], $_POST['amount'], $_POST['category'],
                    $_POST['account'], $_POST['date'], $_POST['edit-expense'])) {

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
                        $err = "Unauthorized access!";
                        $errorArr[] = $error;
                        displayError($errorArr);
                        $check = false;
                    }
                    if ($check == true) {
                        updateExpense($_POST['expense-id'], $_POST['expense-name'], $_POST['amount'],
                            $_POST['category'], $_POST['account'], $_POST['date']);
                        displaySuccessMessage("Success! Edition saved!");
                        header("refresh:2;url=./index.php");
                    } else {
                        displayError($errorArr);
                    }
                }
            ?>
            <div class="ex-form">
                <!-- Title of the form -->
                <h2>Edit Expense  <i class="fa-solid fa-pen-to-square fa-beat"></i></h2>
                <!-- Form containing different inputs spread into separate divs for the ease of styling -->
                <!-- Inputs include the name, amount, category, type of payment and date  -->
                <form action="./editexpense.php" method="post" id="edit-expense-form">
                    <input type="hidden" id="expense-id" name="expense-id" value="<?php echo $id; ?>">
                    <div class="ex-name">
                        <label for="expense-name">Name:
                            <input type="text" id="expense-name" name="expense-name"
                                   pattern="[a-zA-z'\-][a-zA-Z0-9 '\-]*" minlength="3"
                                   maxlength="30" autocomplete="on" value="<?php echo $name; ?>" required>
                        </label>
                    </div>
                    <div class="ex-amount">
                        <label for="amount">Amount:
                            <input type="number" id="amount" name="amount" min="0.00"
                                   step="0.01" autocomplete="on" value="<?php echo $amount; ?>" required>
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
                            <input type="date" id="date" name="date" min="2023-01-01"
                                   max="2033-01-01" autocomplete="on" value="<?php echo $date; ?>" required>
                        </label>
                    </div>
                    <button id="edit-expense" class="add-expense-button" type="submit" name="edit-expense">
                        Save  <i class="fa-solid fa-floppy-disk"></i></button>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
    } else {
        header("location:./index.php");
    }
?>