<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
} else if ($_SESSION["userlevel"] != 'admin') {
    echo "<script>alert('No permission.');  document.location.href = 'home.php'; </script>";
} else {
    $id = $_GET["productId"];

    $product = query("SELECT * FROM product WHERE productId = $id")[0];

    if (isset($_POST["submit"])) {
        if (updateprod($_POST) > 0) {
            echo "
                    <script>
                        alert('Succeeded');
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
        <!-- NAVBAR STARTS -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <h4 class="navbar-brand">Oneul Korean Food</h4>
                <nav class="navbar">
                    <form class="container-fluid justify-content-start">

                        <p class="navbar-text">Welcome
                            <?php echo $_SESSION['username']; ?>,
                            <a href="logout.php">Logout</a>
                        </p>
                    </form>
                </nav>
            </div>
        </nav>
        <!-- NAVBAR ENDS -->
        <!-- MAIN BODY STARTS -->
        <div class="container">
            <h1 class="center mt-5 mb-1">Edit Product</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?= $product["productId"]; ?>">
            <input type="hidden" name="oldimg" value="<?= $product["productImage"]; ?>">
            <table align="center">
                <tr>
                    <td>
                        <label for="productName">Name : </label>
                    </td>
                    <td>
                        <input type="text" name="productName" id="productName" required value="<?= $product["productName"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productStock">Stock : </label>
                    </td>
                    <td>
                        <input type="number" name="productStock" id="productStock" min=1 required value="<?= $product["productStock"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="productPrice">Price : RM</label>
                    </td>
                    <td>
                        <input type="number" name="productPrice" id="productPrice" min=0.00 step="0.01"  required value="<?= $product["productPrice"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="imageprod">Image : </label>
                    </td>
                    <td>
                        <img src="product_images/<?= $product["productImage"]; ?>" alt="" width="100"><br>
                        <input type="file" name="imageprod" id="imageprod">
                    </td>
                </tr>
            </table>
            <br>
            <div align="center">
                <button class="btn btn-success" type="submit" name="submit">Save</button>
                <a class="btn btn-secondary" type="button" href="home.php" name="cancel">Cancel</a>
            </div>
        </form>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>