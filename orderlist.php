<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
    exit;
} else {
    $getQuery = "SELECT * FROM order_table o JOIN runner r ON (o.runnerId = r.runnerId)";
    $order_table = query($getQuery);
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
                <h1 class="center mt-5 mb-1">Order List | Admin View</h1>
            <?php } else if ($_SESSION['userlevel'] == 'runner') { ?>
                    <h1 class="center mt-5 mb-1">Order List | Runner View</h1>
            <?php } else { ?>
                    <h1 class="center mt-5 mb-1">Order List | Customer View</h1>
            <?php } ?>
        </div>
        <table class="table table-striped" id="table" align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Product List</th>
                    <th>Total Price</th>
                    <th>Runner</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            $counter = 0;
            foreach ($order_table as $order):
                $counter++;
                ?>
                <tr>
                    <td>
                        <?php echo $counter; ?>
                    </td>
                    <td>
                        <?php echo $order["orderCreatedDate"], "<br>"; ?>
                    </td>
                    <td>
                        <?php
                        $total = 0.0;
                        $getQuery = "SELECT * FROM cart c JOIN order o ON (o.orderId = c.orderId) JOIN product p ON (p.productId = o.productId)";
                        $cart = query($getQuery); 
                        foreach ($cart as $c) {
                            $total += ($c["productPrice"] * $c["productQuantity"]);
                            echo $c["productName"], "<br>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo "RM", number_format((float) $total, 2), "<br>"; ?>
                    </td>
                    <td>
                        <?php echo $cart["runnerUsername"], "<br>"; ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['userlevel'] == 'admin') { ?>
                            <a href='editproduct.php?productId=<?php echo $prod["productId"]; ?>'
                                class='btn btn-warning'>Edit</a>
                            <a href='deleteproduct.php?productId=<?php echo $prod["productId"]; ?>'
                                class='btn btn-danger'>Delete</a>
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