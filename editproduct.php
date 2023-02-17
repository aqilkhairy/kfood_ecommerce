<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echoSwal("Please login first.", "document.location.href = 'login.php';");
} else if ($_SESSION["userlevel"] != 'admin') {
    echoSwal("No permission.", "document.location.href = 'home.php';");
} else {
    $id = $_GET["productId"];

    $product = query("SELECT * FROM product WHERE productId = $id")[0];

    if (isset($_POST["submit"])) {
        if (updateprod($_POST) > 0) {
            echo "
                    <script>
                        document.location.href = 'home.php';
                    </script>
                    ";
        } else {
            echoSwal("Database query failed", "document.location.href = 'home.php';");
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
            <h1 class="center mt-5 mb-1">Edit Product</h1>
        </div>
        <form class="form-group" style="margin-left: 20%;margin-right: 20%" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?= $product["productId"]; ?>">
            <input type="hidden" name="oldimg" value="<?= $product["productImage"]; ?>">
            <table class="table" align="center" >
                <tr>
                    <td>
                        <label for="productName">Name : </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="productName" id="productName" required value="<?= $product["productName"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productStock">Stock : </label>
                    </td>
                    <td>
                        <input class="form-control" type="number" name="productStock" id="productStock" min=0 required value="<?= $product["productStock"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productPrice">Price : RM</label>
                    </td>
                    <td>
                        <input class="form-control" type="number" name="productPrice" id="productPrice" min=0.00 step="0.01"  required value="<?= $product["productPrice"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="imageprod">Image : </label>
                    </td>
                    <td>
                        <img src="product_images/<?= $product["productImage"]; ?>" alt="" width="100"><br>
                        <input class="form-control" type="file" name="imageprod" id="imageprod">
                    </td>
                </tr>
            </table>
            <br>
            <div align="center" >
                <button class="form-control btn btn-success" type="submit" name="submit">Save</button>
                <a class="form-control btn btn-secondary" type="button" href="home.php" name="cancel">Cancel</a>
            </div>
        </form>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>