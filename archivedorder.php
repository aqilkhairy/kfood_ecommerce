<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login first.');  document.location.href = 'login.php'; </script>";
    exit;
} else {
    if($_SESSION["userlevel"] != 'admin') {
        echo "<script>alert('Redirecting to home...');  document.location.href = 'home.php'; </script>";
    }
}
if (isset($_GET["deleteOrder"])) {
    $id = $_GET["orderId"];
    if (deleteOrder($id) > 0) { 
        echo "
                <script>
                    document.location.href = 'archivedOrder.php';
                </script>
                ";
    } else {
        echo "
                <script>
                    alert('Something wrong');
                    document.location.href = 'archivedOrder.php';
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
                JOIN cart c
                ON o.orderId = c.orderId
                JOIN customer d
                ON c.custId = d.custId
                LEFT JOIN ( SELECT DISTINCT runnerId, runnerUsername FROM runner ) r
                on o.runnerId = r.runnerId
                WHERE o.orderStatus = 'COMPLETED' OR o.orderStatus = 'CANCELED' 
                GROUP BY o.orderId";
                $order_table = query($getQuery);
                ?>
                <h1 class="center mt-5 mb-1">Archived Order</h1>
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
                        <td>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"
                                    data-orderId="<?php echo $orderId; ?>">
                                    Delete Record
                                </button>
                        </td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
    <!-- Small modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <a href='archivedorder.php' class='deleteButton btn btn-danger'>Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#deleteModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                orderId = $(e.relatedTarget).data('orderid');
            var link = 'archivedorder.php?deleteOrder=true&orderId=';
            link += orderId;
            $modal.find(".deleteButton").attr("href", link);
        });
    </script>
</body>

</html>