<?php

$conn = mysqli_connect("localhost", "root", "", "kfood");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

function echoSwal($message, $then) {
    echo "<script>
            document.onreadystatechange = function () {
                if (document.readyState == \"complete\") {
                    swal('$message').then((value) => {
                            $then
                        });
              }
            }
            </script>";
}

function query($query)
{
    global $conn;

    $result = mysqli_query($conn, $query);

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function changeStock($productId, $newStock)
{
    global $conn;

    $query = "UPDATE product SET productStock = $newStock WHERE productId = $productId";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addprod($data)
{
    global $conn;

    $name = $data["productName"];
    $price = $data["productPrice"];
    $stock = $data["productStock"];

    $imageprod = upload();
    if (!$imageprod) {
        return false;
    }

    $query = "INSERT INTO product(productName, productPrice, productStock, productImage) VALUES ('$name',$price,$stock,'$imageprod')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addcart($data)
{
    global $conn;

    $productId = $data["productId"];
    $productQuantity = $data["productQuantity"];
    $productNote = $data["productNote"];
    $custId = $data["custId"];

    $query = "INSERT INTO cart(productId, productQuantity, productNote, custId) VALUES ($productId, $productQuantity, '$productNote', $custId)";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function addorder($data)
{
    global $conn;

    $query = "INSERT INTO order_table(orderStatus) VALUES ('ORDER PLACED')";
    mysqli_query($conn, $query);
    $orderId = mysqli_insert_id($conn);

    foreach ($data['cartArray'] as $d):
        $cartId = $d;

        $query = "UPDATE cart SET
                    orderId = $orderId
                    WHERE cartId = $cartId
        ";

        mysqli_query($conn, $query);
    endforeach;

    return mysqli_affected_rows($conn);
}

function updatecart($data)
{
    global $conn;

    $cartId = $data["cartId"];
    $productQuantity = $data["productQuantity"];
    $productNote = $data["productNote"];

    $query = "UPDATE cart SET
                    productQuantity = '$productQuantity',
                    productNote = '$productNote' 
                    WHERE cartId = $cartId
        ";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function deleteCart($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM cart WHERE cartId = $id");

    return mysqli_affected_rows($conn);
}

function modifyOrderStatus($orderId, $status)
{
    global $conn;

    $query = "UPDATE order_table SET
                    orderStatus = '$status' 
                    WHERE orderId = $orderId
        ";

    mysqli_query($conn, $query);

    if ($status == 'CANCELED') {
        $query = "SELECT * FROM cart c JOIN customer b ON (c.custId = b.custId) JOIN product p ON (c.productId = p.productId) WHERE c.orderId = $orderId";
        $rows = query($query);
        foreach ($rows as $r) {
            $productId = $r["productId"];
            $productStock = $r["productStock"];
            $productQuantity = $r["productQuantity"];
            $newStock = $productStock + $productQuantity;
            $query = "UPDATE product SET
                    productStock = $newStock
                    WHERE productId = $productId
        ";
            mysqli_query($conn, $query);
        }
    }

    return mysqli_affected_rows($conn);
}

function chooseRunner($orderId, $runnerId)
{
    global $conn;

    $query = "UPDATE order_table SET
                    runnerId = $runnerId 
                    WHERE orderId = $orderId
        ";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function upload()
{
    $filename = $_FILES['imageprod']['name'];
    $filesize = $_FILES['imageprod']['size'];
    $error = $_FILES['imageprod']['error'];
    $filetmpname = $_FILES['imageprod']['tmp_name'];

    if ($error === 4) {
        echoSwal("Image not supported.", "");

        return false;
    }

    $fileextensionallowed = ['jpg', 'jpeg', 'png'];
    $fileextension = explode('.', $filename);
    $fileextension = strtolower(end($fileextension));

    if (!in_array($fileextension, $fileextensionallowed)) {
        echoSwal("Image not supported.", "");

        return false;
    }

    if ($filesize > 5000000) {
        echoSwal("Image file too big.", "");

        return false;
    }

    $newfilename = uniqid();
    $newfilename .= '.';
    $newfilename .= $fileextension;
    move_uploaded_file($filetmpname, 'product_images/' . $newfilename);

    return $newfilename;
}

function deleteprod($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM product WHERE productId = $id");

    return mysqli_affected_rows($conn);
}

function deleteOrder($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM cart WHERE orderId = $id");
    mysqli_query($conn, "DELETE FROM order_table WHERE orderId = $id");

    return mysqli_affected_rows($conn);
}

function updateprod($data)
{
    global $conn;

    $prod_id = $data["productId"];
    $name = $data["productName"];
    $price = $data["productPrice"];
    $stock = $data["productStock"];
    $oldimg = $data["oldimg"];

    if ($_FILES['imageprod']['error'] === 4) {
        $imageprod = $oldimg;
    } else {
        $imageprod = upload();
    }
    $query = "UPDATE product SET
                    productName = '$name',
                    productPrice = $price,
                    productStock = $stock,
                    productImage = '$imageprod'
                    WHERE productId = $prod_id
        ";

    mysqli_query($conn, $query);
    //delete file soon
    return mysqli_affected_rows($conn);
}