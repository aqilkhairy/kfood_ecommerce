<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echoSwal("Please login first.", "document.location.href = 'login.php';");
    exit;
} else {
    $custId = $_SESSION["custId"];
    $getQuery = "SELECT * FROM cart c JOIN customer b ON (c.custId = b.custId) JOIN product p ON (c.productId = p.productId) WHERE c.orderId IS NULL AND c.custId = $custId";
    $mycart = query($getQuery);
    if ($_SESSION["userlevel"] == "runner") {
        echo "<script>document.location.href = 'runnerhome.php';</script>";
    }

    if (isset($_POST["modifyCart"])) {
        $_POST["custId"] = $_SESSION["custId"];
        if (updatecart($_POST) > 0) {
            echo "
                    <script>
                        document.location.href = 'mycart.php';
                    </script>
                    ";
        } else {
            echoSwal("Database query failed", "document.location.href = 'mycart.php';");
        }
    }
    if(isset($_POST["checkout"])) {
        $i = 0;
        $cartArray = [];
        foreach ($mycart as $cart):
            $cartArray[$i] = $cart["cartId"];
            $i++;

            $newStock = $cart["productStock"]-$cart["productQuantity"];
            changeStock($cart["productId"],$newStock);
        endforeach;
        $_POST['cartArray'] = $cartArray;
        if (addorder($_POST) > 0) {
            echo "
                    <script>
                        document.location.href = 'mycart.php';
                    </script>
                    ";
        } else {
            echoSwal("Database query failed", "document.location.href = 'mycart.php';");
        }
    }
    if (isset($_POST["removeCart"])) {
        if (deleteCart($_POST["cartId"]) > 0) {
            echo "
                    <script>
                        document.location.href = 'mycart.php';
                    </script>
                    ";
        } else {
            echoSwal("Database query failed", "document.location.href = 'mycart.php';");
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
            <div class="row">
                <div class="col-md-7">
                    <h1 class="center mt-5 mb-1">My Cart</h1>
                    <table class="table table-striped" id="table" align="center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Details</th>
                                <th>Quantity</th>
                                <th>Price Each</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                        $totalprice = 0.0;
                        $counter = 0;
                        $cartArray = [];
                        foreach ($mycart as $cart):
                            $cartArray[$counter] = $cart["cartId"];
                            $counter++;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $counter; ?>
                                </td>
                                <td>
                                    <?php echo $cart["productName"], "<br>"; ?>
                                    <?php if($cart["productNote"] != "") { ?>
                                        <i><span style="color: gray;">"
                                            <?php echo $cart["productNote"]; ?> "
                                        </span></i>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php echo $cart["productQuantity"], "<br>"; ?>
                                </td>
                                <td>
                                    <?php
                                    $price = ($cart["productQuantity"] * $cart["productPrice"]);
                                    $totalprice += $price;
                                    echo "RM", number_format((float) $cart["productPrice"], 2), "<br>";
                                    ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#modifyModal" data-cartId="<?php echo $cart["cartId"]; ?>"
                                        data-productId="<?php echo $cart["productId"]; ?>"
                                        data-productName="<?php echo $cart["productName"]; ?>"
                                        data-productQuantity="<?php echo $cart["productQuantity"]; ?>"
                                        data-productNote="<?php echo $cart["productNote"]; ?>">
                                        Modify
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#removeModal" data-cartId="<?php echo $cart["cartId"]; ?>">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            <?php
                        endforeach; ?>
                    </table>
                </div>
                <div class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Order Summary</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <b>Quantity</b>
                                    </div>
                                    <div class="col-md-7">
                                        <b>Product</b>
                                    </div>
                                    <div class="col-md-3" style="padding-right: 20px; display: flex; justify-content: flex-end">
                                        <b>Total Price</b>
                                    </div>
                                </div>
                                <hr>
                                <?php 
                                foreach ($mycart as $cart):
                                    ?>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <span style="color: gray;">
                                                <?php echo $cart["productQuantity"], "x"; ?>
                                            </span>
                                        </div>
                                        <div class="col-md-7">
                                            <?php echo $cart["productName"], "<br>"; ?>
                                            <?php if($cart["productNote"] != "") { ?>
                                                <i><span style="color: gray;">"
                                                    <?php echo $cart["productNote"]; ?> "
                                                </span></i>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-3" style="padding-right: 20px; display: flex; justify-content: flex-end">
                                            <?php
                                            $price = ($cart["productQuantity"] * $cart["productPrice"]);
                                            echo "", number_format((float) $price, 2), "<br>";
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                endforeach; ?>
                                <div class="row">
                                    <hr>
                                    <div class="col-md-9">
                                        Subtotal:
                                    </div>
                                    <div class="col-md-3" style="padding-right: 20px; display: flex; justify-content: flex-end">
                                        <b>
                                            <?php echo "RM", number_format((float) $totalprice, 2); ?>
                                        </b>
                                    </div>
                                </div>
                                <div class="row">
                                    <hr>
                                    <div class="col-md-4">
                                        Payment Type:
                                    </div>
                                    <div class="col-md-8" style="padding-right: 20px; display: flex; justify-content: flex-end">
                                        Cash on Delivery &nbsp;&nbsp;<input type="radio" value="Cash on Delivery" checked/> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8" style="padding-right: 20px; display: flex; justify-content: flex-end">
                                        Online Payment (not available for now) &nbsp;&nbsp;<input type="radio" value="Online Payment (not available for now)" disabled/> 
                                    </div>
                                </div>
                                <div class="row">
                                    <br><p class="center">
                                    <button <?php
                                    if($counter <= 0) echo 'disabled';
                                    ?> style="width: 80%;" type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#checkoutModal">
                                        Checkout
                                    </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- MAIN BODY ENDS -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modifyModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <form method="post" action="mycart.php">
                    <div class="modal-body">
                        <div class="input-group">
                            <input type="hidden" id="modifyCart" name="modifyCart">
                            <input type="hidden" id="cartId" name="cartId">
                            <input type="hidden" id="productId" name="productId">
                            <p>Amount: <input class="form-control" id="productQuantity" name="productQuantity"
                                    type="number" value="1" min="1"></p>
                            <p>Additional Note: <textarea class="form-control" id="productNote"
                                    name="productNote"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#modifyModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                cartId = $(e.relatedTarget).data('cartid');
            productId = $(e.relatedTarget).data('productid');
            productName = $(e.relatedTarget).data('productname');
            productQuantity = $(e.relatedTarget).data('productquantity');
            productNote = $(e.relatedTarget).data('productnote');
            $modal.find('.modal-title').html(productName);
            $modal.find('#cartId').val(cartId);
            $modal.find('#productId').val(productId);
            $modal.find('#productQuantity').val(productQuantity);
            $modal.find('#productNote').html(productNote);
            console.log(productName);
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    Order received, thank you.
                    <form id="checkoutForm" action="mycart.php" method="POST">
                        <input type="hidden" id="checkout" name="checkout">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#checkoutModal').on('hidden.bs.modal', function (e) {
            var $modal = $(this);
            $("#checkoutForm").submit();
        });
    </script>

    <!-- Small modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Remove Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure want to remove this item from cart?
                </div>
                <div class="modal-footer">
                    <form method="post" action="mycart.php">
                        <input type="hidden" id="removeCart" name="removeCart">
                        <input type="hidden" id="cartId" name="cartId">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <input class='btn btn-danger' type="submit" value="Yes, Remove">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#removeModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                cartId = $(e.relatedTarget).data('cartid');
            $modal.find('#cartId').val(cartId);
        });
    </script>
</body>

</html>