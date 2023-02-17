<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echoSwal("Please login first.", "document.location.href = 'login.php';");
} else if ($_SESSION["userlevel"] != 'admin') {
    echoSwal("No permission.", "document.location.href = 'home.php';");
} else {
    if (isset($_POST["submit"])) {
        if (addprod($_POST) > 0) {
            echo "
                    <script>
                        document.location.href = 'home.php';
                    </script>
                    ";
        } else {
            echoSwal("Database query failed.", "document.location.href = 'home.php';");        
        }
    }
}
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
    <?php include('navbar.php'); ?>
        <!-- MAIN BODY STARTS -->
        <div class="container">
            <h1 class="center mt-5 mb-1">Add New Product</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <table align="center">
                <tr>
                    <td>
                        <label for="productName">Name : </label>
                    </td>
                    <td>
                        <input type="text" name="productName" id="productName" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productStock">Stock : </label>
                    </td>
                    <td>
                        <input type="number" name="productStock" id="productStock" min=1 required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productPrice">Price : RM</label>
                    </td>
                    <td>
                        <input type="number" name="productPrice" id="productPrice" min=0.00 step="0.01" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="imageprod">Image : </label>
                    </td>
                    <td>
                        <input type="file" name="imageprod" id="imageprod" required>
                    </td>
                </tr>
            </table>
            <br>
            <div align="center">
                <button type="submit" name="submit">Add Product</button>
            </div>
        </form>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>