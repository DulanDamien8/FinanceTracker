<?php
    if (empty($_SESSION['uuid'])) {
?>
<header>
    <!-- Navbar contains an unordered list of links with references to different locations of the webpage -->
    <nav class="mynav">
        <a id="titlelnk" class="titlelink" href="./index.php">
            <i class="fa-solid fa-wallet"></i> iWallet
        </a>
        <div>
            <ul class="navbar">
                <li>
                    <a id="loginhref" href="./login.php">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                </li>
                <li>
                    <a id="abouthref" href="./about.php">
                        <i class="fa-solid fa-circle-info"></i> About
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<?php
    } else {
?>
<!-- The header contains the navigation bar which is used to change the location on the webpage -->
<header>
    <!-- Navbar contains an unordered list of links with references to different locations of the webpage -->
    <nav class="mynav">
        <a id="titlelnk" class="titlelink" href="./index.php">
            <i class="fa-solid fa-wallet"></i> iWallet
        </a>
        <div>
            <ul class="navbar">
                <li>
                    <a id="homehref" href="./index.php">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>
                <li>
                    <a id="myacchref" href="./myaccount.php">
                        <i class="fa-solid fa-user"></i> MyAccount
                    </a>
                </li>
                <li>
                    <a id="logouthref" href="./components/logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<?php } ?>
