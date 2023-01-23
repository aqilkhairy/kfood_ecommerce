<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
    exit;
} else {
    $getQuery = "SELECT * FROM product";
    $product = query($getQuery);
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
            <?php if ($_SESSION['userlevel'] == 'admin') { ?>
                <h1 class="center mt-5 mb-1">Product List | Admin View</h1>
                <p class="center"><a class="btn btn-success" type="button" href="addproduct.php">Add New Product</a></p>
            <?php } else { ?>
                <h1 class="center mt-5 mb-1">Welcome to Oneul Korean Food</h1>
                <p class="center mb-5">Order and enjoy our korean food anytime, anywhere!</p>
            <?php } ?>
        </div>
        <table class="table table-striped" id="table" align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Stock Available</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            $counter = 0;
            foreach ($product as $prod):
                $counter++;
                ?>
                <tr>
                    <td>
                        <?php echo $counter; ?>
                    </td>
                    <td>
                        <img src="product_images/<?= $prod["productImage"]; ?>" alt="" width="100"><br>
                    </td>
                    <td>
                        <?php echo $prod["productName"], "<br>"; ?>
                    </td>
                    <td>
                        <?php echo "RM", number_format((float) $prod["productPrice"], 2), "<br>"; ?>
                    </td>
                    <td>
                        <?php echo $prod["productStock"], "<br>"; ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['userlevel'] == 'admin') { ?>
                            <a href='editproduct.php?productId=<?php echo $prod["productId"]; ?>' class='btn btn-warning'>Edit</a>
                            <a href='deleteproduct.php?productId=<?php echo $prod["productId"]; ?>' class='btn btn-danger'>Delete</a>
                        <?php } else { ?>
                            <!-- CUSTOMER ACTION GOES HERE -->
                        <?php } ?>
                    </td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>