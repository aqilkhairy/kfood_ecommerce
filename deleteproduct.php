<?php

require 'fx.php';
session_start();
$id = $_GET["productId"];
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
} else if ($_SESSION["userlevel"] != 'admin') {
    echo "<script>alert('No permission.');  document.location.href = 'home.php'; </script>";
} else {
    if (deleteprod($id) > 0) {
        echo "
            <script>
                document.location.href = 'home.php';
            </script>
    ";
    } else {
        echo "
            <script>
                alert('Something wrong');
                document.location.href = 'home.php';
            </script>
    ";
    }
}
?>