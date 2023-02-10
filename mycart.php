<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
    exit;
} else {
    $getQuery = "SELECT * FROM cart c JOIN customer b ON (c.custId = b.custId) JOIN product p ON (c.productId = p.productId);";
    $mycart = query($getQuery);
    if ($_SESSION["userlevel"] == "runner") {
        echo "<script>document.location.href = 'runnerhome.php';</script>";
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
            <h1 class="center mt-5 mb-1">My Cart</h1>
        </div>
        <table class="table table-striped" id="table" align="center">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            $totalprice = 0.0;
            foreach ($product as $prod):
                ?>
                <tr>
                    <td>
                        <img src="product_images/<?= $mycart["productImage"]; ?>" alt="" width="100"><br>
                    </td>
                    <td>
                        <?php echo $mycart["productName"], "<br>"; ?>
                    </td>
                    <td>
                        <?php echo $mycart["productQuantity"], "<br>"; ?>
                    </td>
                    <td>
                        <?php
                        $price = ($mycart["productQuantity"] * $mycart["productPrice"]);
                        $totalprice += $price;
                        echo "RM", number_format((float) $price, 2), "<br>"; 
                        ?>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>