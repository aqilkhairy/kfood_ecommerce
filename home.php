<?php
require 'fx.php';

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echoSwal("Please login first.", "document.location.href = 'login.php';");
    exit;
} else {
    $getQuery = "SELECT * FROM product";
    $product = query($getQuery);
    $getQuery = "SELECT * FROM cart c JOIN customer b ON (c.custId = b.custId);";
    $mycart = query($getQuery);
    if ($_SESSION["userlevel"] == "runner") {
        echo "<script>document.location.href = 'orderlist.php';</script>";
    }

    if (isset($_POST["productId"])) {
        $_POST["custId"] = $_SESSION["custId"];
        if (addcart($_POST) > 0) {
            echoSwal("Added to cart.", "document.location.href = 'home.php';");
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
    <div class="main table-responsive ">
        <?php include('navbar.php'); ?>
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
                            <a href='editproduct.php?productId=<?php echo $prod["productId"]; ?>'
                                class='btn btn-warning'>Edit</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"
                                data-productId="<?php echo $prod["productId"]; ?>">
                                Delete
                            </button>
                        <?php } else {
                            if ($prod["productStock"] <= 0) { ?>
                                <button type="button" class="btn btn-secondary" disabled>
                                    OUT OF STOCK
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addToCartModal"
                                    data-productId="<?php echo $prod["productId"]; ?>"
                                    data-productName="<?php echo $prod["productName"]; ?>">
                                    Add to Cart
                                </button>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <!-- MAIN BODY ENDS -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addToCartModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <form method="post" action="home.php">
                    <div class="modal-body">
                        <div class="input-group">
                            <input type="hidden" id="productId" name="productId">
                            <p>Amount: <input class="form-control" id="productQuantity" name="productQuantity"
                                    type="number" value="1" min="1"></p>
                            <p>Additional Note: <textarea class="form-control" id="productNote"
                                    name="productNote"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#addToCartModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                productId = $(e.relatedTarget).data('productid');
            productName = $(e.relatedTarget).data('productname');
            $modal.find('.modal-title').html(productName);
            $modal.find('#productId').val(productId);
            console.log(productName);
        });
    </script>

    <!-- Small modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <a href='deleteproduct.php' class='deleteButton btn btn-danger'>Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#deleteModal').on('show.bs.modal', function (e) {
            var $modal = $(this),
                productId = $(e.relatedTarget).data('productid');
            var link = 'deleteproduct.php?productId=';
            link += productId;
            $modal.find(".deleteButton").attr("href", link);
        });
    </script>

</body>

</html>