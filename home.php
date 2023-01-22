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
                        <p class="navbar-text">Welcome <?php echo $_SESSION['username']; ?>,
                            <a href="logout.php">Logout</a>
                        </p>
                    </form>
                </nav>
            </div>
        </nav>
        <!-- NAVBAR ENDS -->
        <!-- MAIN BODY STARTS -->
        <div class="container">
            <h1 class="center mt-5 mb-1">Welcome to Oneul Korean Food</h1>
            <p class="center mb-5">Order and enjoy our korean food anytime, anywhere!</p>
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
                    <td> <?php echo $counter; ?> </td>
                    <td></td>
                    <td> <?php echo $prod["productName"], "<br>"; ?> </td>
                    <td> <?php echo "RM", number_format((float) $prod["productPrice"], 2), "<br>"; ?> </td>
                    <td> <?php echo $prod["productStock"], "<br>"; ?> </td>
                    <td> </td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
</body>

</html>