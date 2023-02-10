<?php

$conn = mysqli_connect("localhost", "root", "", "kfood");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
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

function getTotalRow($query)
{
    global $conn;

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_row($result);
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

function upload()
{
    $filename = $_FILES['imageprod']['name'];
    $filesize = $_FILES['imageprod']['size'];
    $error = $_FILES['imageprod']['error'];
    $filetmpname = $_FILES['imageprod']['tmp_name'];

    if ($error === 4) {
        echo "<script> alert('Image not Uploaded'); </script>";

        return false;
    }

    $fileextensionallowed = ['jpg', 'jpeg', 'png'];
    $fileextension = explode('.', $filename);
    $fileextension = strtolower(end($fileextension));

    if (!in_array($fileextension, $fileextensionallowed)) {
        echo "<script> alert('Not a image file Uploaded'); </script>";

        return false;
    }

    if ($filesize > 5000000) {
        echo "<script> alert('Image file too big'); </script>";

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

function searchprod($keyword)
{
    $query = "SELECT * FROM product WHERE name LIKE '%$keyword%'";

    return query($query);
}

function buyprod($data)
{
    global $conn;

    $cust_name = $data["cust_name"];
    $address = $data["address"];
    $num_phone = $data["num_phone"];
    $prod_id = $data["prod_id"];
    $quantity = $data["quantitybuy"];
    $total_price = $data["price"] * $data["quantitybuy"];

    $query = "INSERT INTO sales_record VALUES ('$cust_name','','$address','$num_phone',$prod_id,$quantity,$total_price)";

    mysqli_query($conn, $query);

    $oldquantity = $data["quantity"];

    $updatedquantity = $oldquantity - $quantity;

    $query = "UPDATE product SET quantity = $updatedquantity WHERE prod_id = $prod_id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function addsales($data)
{
    global $conn;
    $getQuery = "SELECT * FROM product";
    $product = mysqli_query($conn, $getQuery);

    $username = $_SESSION["customer"];
    $query2 = "INSERT INTO sales_record (username) VALUES ('$username'); ";
    mysqli_query($conn, $query2);
    $salesrecord_id = mysqli_insert_id($conn);

    $total = 0.0;
    $cart = $_SESSION["cart"];
    $prod_id = array_column($cart, "idc");
    $quantity = $_POST["quantity"];
    $no = 0;
    foreach ($product as $prod):
        for ($i = 0; $i < count($prod_id); $i++) {
            if ($prod["prod_id"] == $prod_id[$i]) {
                $query = "INSERT INTO cart (salesrecord_id, prod_id, cart_quantity) VALUES ($salesrecord_id, $prod_id[$i], $quantity[$no]); ";
                $minusquantity = $quantity[$no];

                $total += ((float) $prod["price"] * (int) $quantity[$no]);
                $no++;
                mysqli_query($conn, $query);


                $query3 = "UPDATE product SET quantity = quantity - " . $minusquantity . " WHERE prod_id = $prod_id[$i]";
                mysqli_query($conn, $query3);
            }
        }
    endforeach;

    $query2 = "UPDATE sales_record SET total_price=$total WHERE salesrecord_id=$salesrecord_id;";
    mysqli_query($conn, $query2);
    return mysqli_affected_rows($conn);
}