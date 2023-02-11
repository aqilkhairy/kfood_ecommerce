<?php
require 'fx.php';

session_start();
?>


<!DOCTYPE html>

<head>
    <?php
    include('include.php');
    ?>
    <title>Oneul Korean Food</title>
</head>

<body>
    <div class="main">
        <!-- NAVBAR STARTS -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <h4 class="navbar-brand">Oneul Korean Food</h4>
            </div>
        </nav>
        <!-- NAVBAR ENDS -->
        <!-- MAIN BODY STARTS -->
        <div class="container">
            <h1 class="center mt-5 mb-1">Welcome to Oneul Korean Food</h1>
            <p class="center mb-5">Order and enjoy our korean food anytime, anywhere!</p>
            <p class="center mb-5">Please login to continue.</p>
            <p class="center mb-5"><a class="btn btn-default me-2" type="button" href="register.php">Register</a>
             <a class="btn btn-default me-2" type="button" href="login.php">Login</a></p>
        </div>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>