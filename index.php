<?php
    session_start();

    $errArr = array();
    $totalExpenses = 0.00;

    //function to check if income is not smaller or equal to 0
    function validateIncome($income): bool
    {
        if ($income > 0.0 && $income < 1000000.0) {
            return true;
        } else {
            return false;
        }
    }

    //function to display success message
    function displaySuccessMessage($message): void
    {
        echo '
                    <div id="successMsg">
                        <b>'.$message.'</b>
                    </div>
                ';
    }

    //function to display error message from the error array
    function displayError($error)
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

    //function to download the expenses list as a csv file
    function downloadExpensesList(): void
    {
        $file = "./data.csv";
        $uuid = $_SESSION['uuid'];
        if (!file_exists($file)) {
            touch($file);
            chmod($file, 0766);
        }
        $conn = require './data/database.php';
        $sql = "SELECT * FROM shopping WHERE UserID='$uuid'";
        $result = $conn->query($sql);
        $conn->close();
        if (!empty($result)) {
            $handle = fopen($file, 'w');
            foreach ($result as $value) {
                $lineArr = array($value['Name'], $value['Amount'], $value['Type'], $value['Payment'], $value['Date']);
                fputcsv($handle, $lineArr,";");
            }
            fclose($handle);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=shopping.csv');
            readfile("./data.csv");
            exit();
        } else {
            $errorArr[] = "Shopping list empty";
            displayError($errorArr);
        }

    }

    //if the button to download is clicked we initialise download
    if ($_POST && isset($_POST['download-expenses'])) {
        downloadExpensesList();
    }

    //function which gets the income from the database for the logged in user
    function getIncome(): float {
        $conn = require './data/database.php';
        $uuid = $_SESSION['uuid'];
        $sql = "SELECT * FROM income WHERE UserID='$uuid' LIMIT 1";
        $usersIncome = $conn->query($sql);
        $conn->close();
        $income = 0.00;
        foreach($usersIncome as $value) {
            $income += floatval($value['Amount']);
        }
        return $income;
    }

    //function which updates the income for the logged in user
    function addIncome($income): void {
        $conn = require './data/database.php';
        $uuid = $_SESSION['uuid'];
        $sql = "UPDATE income SET Amount = TRUNCATE('$income', 2) WHERE UserID = '$uuid'";
        if ($conn->query($sql) === TRUE) {
            displaySuccessMessage("Added income");
            echo "<meta http-equiv='refresh' content='2'>";
        } else {
            $errArr[] = "Cannot update.";
            displayError($errArr);
        }
        $conn->close();

    }

    function resetIncome() {
        $conn = require './data/database.php';
        $uuid = $_SESSION['uuid'];
        $sql = "UPDATE income SET Amount = TRUNCATE(0.00, 2) WHERE UserID = '$uuid'";
        if ($conn->query($sql) === TRUE) {
            displaySuccessMessage("Income reset!");
            echo "<meta http-equiv='refresh' content='2'>";
        } else {
            $errArr[] = "Cannot reset.";
            displayError($errArr);
        }
        $conn->close();
    }

    //function to calculate the balance of the logged in user
    function calculateBalance($income, $expenses): float {
        return $income - $expenses;
    }

    //if user is authorized we display the homepage, else we redirect to login page
    if(!empty($_SESSION['uuid']) && $_COOKIE['authorized'] === "1") {

?>
<!Doctype html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Marek Kopania">
        <meta name="author" content="Mattias Nomm">
        <meta name="author" content="Dulan Damien Candauda Arachchiege">
        <title>Home</title>
        <link rel="stylesheet" href="./styles/navbar.css">
        <link rel="stylesheet" href="./styles/home.css">
        <link rel="stylesheet" href="./styles/ribbon.css">
        <link rel="stylesheet" href="./styles/success_error_ribbon.css">
        <!-- The script below is for linking FontAwesome icon library to the page -->
        <script src="https://kit.fontawesome.com/fb9880c91d.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="./javascript/ribbon.js" async></script>
    </head>
    <body>
        <noscript>Your browser does not support JavaScript!</noscript>
        <?php include './components/navbar.php'; ?>
        <!-- Main div for the content of the page below navigation bar -->
        <div class="container">
            <div class="left-side">
                <!-- Div for the headers describing the type of column content -->
                <div class="headers">
                    <div class="nameh">Name</div>
                    <div class="amounth">Amount</div>
                    <div class="typeh">Type</div>
                    <div class="paidh">Paid With</div>
                    <div class="dateh">Date</div>
                    <div class="downBtn">
                        <form action="./index.php" method="post" id="dwnld-form">
                            <button class="download-expense-btn" id="download-expenses" name="download-expenses" type="submit">
                                <i class="fa-solid fa-download"></i> Download
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Div for the list of expenses -->
                <div class="expenses-list">
<!--                    Loading of the expenses from the database-->
                    <?php
                        $conn = require './data/database.php';
                        $uuid = $_SESSION['uuid'];
                        $sql = "SELECT * FROM shopping WHERE UserID='$uuid' ORDER BY Date DESC";
                        $expenses = $conn->query($sql);
                        $conn->close();
                        if (!empty($expenses)) {
                            foreach ($expenses as $value) {
                                $totalExpenses += floatval($value['Amount']);
                    ?>
                    <div class="item">
                        <div class="name"><?php echo $value['Name']; ?></div>
                        <div class="amount"><?php echo $value['Amount'], "$"; ?></div>
                        <div class="type"><?php echo $value['Type']; ?></div>
                        <div class="paid"><?php echo $value['Payment']; ?></div>
                        <div class="date"><?php echo $value['Date']; ?></div>
                        <a class="edtb" href="./editexpense.php?id=<?php echo $value['ID']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a class="delb" href="./delete_expense.php?id=<?php echo $value['ID']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </div>
                    <?php
                            }
                        }
//                        we calculate the income and balance with functions
                        $income = getIncome();
                        $balance = calculateBalance($income, $totalExpenses);
                    ?>
                </div>
            </div>
            <div class="ribbonBtn">
                <button class="ribBtn" ><i class="fa-solid fa-bars fa-beat"></i></button>
            </div>
            <!-- Div for the ribbon on the right side of the page -->
            <div class="ribbon">
                <!-- Div containing the profile avatar with the name of the user which is logged in -->
                <div class="profile">
                    <div class="edit">
                        <img src="img/profile_pic.png" alt="User_Icon" title="Profile-pic">
                    </div>
                    <div class="user-name">
                        <h2><?php echo $_SESSION['name']; ?></h2>
                    </div>
                </div>
                <?php  ?>
                <!-- Two divs for the balance and income numerical data -->
                <div class="balance">
                    <h3>Balance: <?php echo $balance; ?>$</h3>
                </div>
                <div class="expenses">
                    <h3>Expenses: <?php echo $totalExpenses; ?>$</h3>
                </div>
                <!-- Div for the form to add income to the account -->
                <div class="income-input">
                    <form action="./index.php" method="post" class="income-form">
                        <label for="income"></label>
                        <input type="number" id="income" class="income" name="income"
                               min="0.00" step="0.01" autocomplete="on" placeholder="Add Income">
                        <button id="add-income" type="submit" name="add-income" class="addIncBut">
                        Add Income
                        </button>
                        <button id="reset-income" type="submit" name="reset-income" class="resetIncomeBtn">Reset</button>
                    </form>
                </div>
<!--                <div class="reset-income-div">-->
<!--                    <form action="./index.php" method="post" class="reset-income-form">-->
<!--                    </form>-->
<!--                </div>-->
                <!-- Div containing the button which links to the "addexpense.php" page -->
                <div class="action-buttons">
                    <button class="addExpBut" onclick="location.href='./addexpense.php'">Add Expense</button>
                </div>
                <?php
                    //if an add income button is clicked we validate post and argument from form
                    if ($_POST && isset($_POST['income'], $_POST['add-income'])) {
                        $check = true;
                        if (!validateIncome($_POST['income'])) {
                            $error = "Income cannot be negative or bigger than 1,000,000";
                            $errArr[] = $error;
                            $check = false;
                        }
                        if ($_COOKIE['authorized'] !== "1") {
                            $error = "Unauthorized access!";
                            $errArr[] = $error;
                            $check = false;
                        }
                        if ($check == true) {
                            $income += floatval($_POST['income']);
                            addIncome($income);
                        } else {
                            displayError($errArr);
                        }

                    }
                    if ($_POST && isset($_POST['reset-income'])){
                        if ($_COOKIE['authorized'] === "1" && isset($_SESSION['uuid'])) {
                            resetIncome();
                        } else {
                            $error = "Unauthorized access!";
                            $errArr[] = $error;
                            displayError($errArr);
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php
    } else {
        header("Location:./login.php");
    }
?>