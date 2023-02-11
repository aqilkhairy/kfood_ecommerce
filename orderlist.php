<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
    exit;
}
if (isset($_GET["cancelOrder"])) {
    $id = $_GET["orderId"];
    if (modifyOrderStatus($_GET, 'CANCELED') > 0) {
        echo "
                <script>
                    alert('Succeeded');
                    document.location.href = 'orderlist.php';
                </script>
                ";
    } else {
        echo "
                <script>
                    alert('Something wrong');
                    document.location.href = 'orderlist.php';
                </script>
                ";
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
            <?php if ($_SESSION['userlevel'] == 'admin') {
                $getQuery = "SELECT *
                FROM order_table o
                LEFT JOIN ( SELECT DISTINCT runnerId FROM runner ) r
                on o.runnerId = r.runnerId
                WHERE o.orderStatus != 'COMPLETED' AND o.orderStatus != 'CANCELED' ";
                $order_table = query($getQuery);
                ?>
                <h1 class="center mt-5 mb-1">Order List | Admin View</h1>
            <?php } else if ($_SESSION['userlevel'] == 'runner') {
                $runnerId = $_SESSION['runnerId'];
                $getQuery = "SELECT * FROM order_table o LEFT JOIN runner r ON (o.runnerId = r.runnerId) WHERE o.runnerId = $runnerId;";
                $order_table = query($getQuery);
                ?>
                    <h1 class="center mt-5 mb-1">Order List | Runner View</h1>
            <?php } else {
                $custId = $_SESSION['custId'];
                $getQuery = "SELECT * FROM order_table o LEFT JOIN runner r ON (o.runnerId = r.runnerId) JOIN cart c ON (o.orderId = c.orderId) WHERE c.custId = $custId GROUP BY c.orderId;";
                $order_table = query($getQuery);
                ?>
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
                    <th>Status</th>
                    <?php if ($_SESSION['userlevel'] != 'customer') {
                        echo '<th>Action</th>';
                    } ?>
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
                        $orderId = $order["orderId"];
                        $getQuery = "SELECT * FROM cart c JOIN order_table o ON (o.orderId = c.orderId) JOIN product p ON (p.productId = c.productId) WHERE o.orderId = $orderId";
                        $cart = query($getQuery); foreach ($cart as $c) {
                            $total += ($c["productPrice"] * $c["productQuantity"]);
                            echo $c["productQuantity"], 'x ', $c["productName"], ' (<i style=\'color: gray;\'>', $c["productNote"], '</i>)<br>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo "RM", number_format((float) $total, 2), "<br>"; ?>
                    </td>
                    <td>
                        <?php
                        if (isset($order["runnerUsername"])) {
                            echo $order["runnerUsername"], "<br>";
                        } else {
                            echo "<i style='color: gray;'>Runner will be assigned shortly...</i>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $status = $order["orderStatus"];
                        if ($status == 'ORDER PLACED') {
                            echo "<span class='label label-default'>$status</span>";
                        } else if ($status == 'IN KITCHEN') {
                            echo "<span class='label label-info'>$status</span>";
                        } else if ($status == 'ON DELIVERY') {
                            echo "<span class='label label-warning'>$status</span>";
                        } else if ($status == 'COMPLETED') {
                            echo "<span class='label label-success'>$status</span>";
                        } else if ($status == 'CANCELED') {
                            echo "<span class='label label-danger'>$status</span>";
                        }
                        ?>
                    </td>
                    <?php if ($_SESSION['userlevel'] != 'customer') { ?>
                        <td>
                            <?php if ($_SESSION['userlevel'] == 'admin') { ?>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelModal"
                                    data-orderId="<?php echo $orderId; ?>">
                                    Cancel Order
                                </button>
                            <?php } else if ($_SESSION['userlevel'] == 'runner') { ?>

                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
    <!-- Small modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="cancelModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure want to cancel this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <a href='orderlist.php' class='cancelButton btn btn-danger'>Yes, Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#cancelModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                orderId = $(e.relatedTarget).data('orderid');
            var link = 'orderlist.php?cancelOrder=true&orderId=';
            link += orderId;
            $modal.find(".cancelButton").attr("href", link);
        });
    </script>
</body>

</html>