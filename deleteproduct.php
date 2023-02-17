<?php

require 'fx.php';
session_start();
$id = $_GET["productId"];
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echoSwal("Please login first.", "document.location.href = 'login.php';");
} else if ($_SESSION["userlevel"] != 'admin') {
    echoSwal("No permission.", "document.location.href = 'home.php';");
} else {
    if (deleteprod($id) > 0) {
        echo "
            <script>
                document.location.href = 'home.php';
            </script>
    ";
    } else {
        echoSwal("Database query failed", "document.location.href = 'home.php';");
    }
}
?>