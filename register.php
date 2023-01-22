<?php
require 'fx.php';

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement 
        $sql = "SELECT custUsername FROM customer WHERE custUsername = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "<script> 
                alert('Oops! Something went wrong. Please try again later.');  </script>";
            }
        }
        mysqli_stmt_close($stmt);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }


    if (empty(trim($_POST["password2"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["password2"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO customer (custUsername, custPass, custContact, custAddress) VALUES (?, ?, ?, ?) ";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_username,  $param_password, $param_contact, $param_address );

            $param_username = $_POST["username"];
            $param_password = md5($password);
            $param_contact = $_POST["contact"];
            $param_address = $_POST["address"];


            if (mysqli_stmt_execute($stmt)) {
                print('tet');
                echo "
                <script> 
                    alert('Registration is successful!');
                    document.location.href = 'login.php'; 
                </script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again later.');</script>";
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Please fill all the field correctly.');</script>";
    }
    mysqli_close($conn);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include('include.php');
    ?>
    <title>Register</title>
</head>

<body>
    <div class="main">
        <div class="center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1 class="center mt-5">Sign Up</h1>
                <div>
                    <label for="username">Username:</label><br>
                    <input type="text" name="username" id="username">
                </div>
                <div>
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" id="password">
                </div>
                <div>
                    <label for="password2">Password Again:</label><br>
                    <input type="password" name="password2" id="password2">
                </div>
                <div>
                    <label for="contact">Contact No.:</label><br>
                    <input type="text" name="contact" id="contact">
                </div>
                <div>
                    <label for="address">Address:</label><br>
                    <textarea name="address" id="address"></textarea>
                </div>
                <br>
                <button class="btn btn-outline-dark me-2" type="submit">Register</button>
                <footer>Already a member? <a href="login.php">Login here</a></footer>
            </form>
        </div>
    </div>
</body>

</html>