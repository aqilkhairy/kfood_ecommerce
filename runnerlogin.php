<?php
require 'fx.php';

$username = $password = "";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = md5(trim($_POST["password"]));

        $sql = "SELECT * FROM runner WHERE runnerUsername = '$username' and runnerPass = '$password' ";

        $result = mysqli_query($conn, $sql);

        if (!$result)
            die("Database access failed: " . mysqli_connect_error());

        $rows = mysqli_num_rows($result);
        if ($rows) {
            $row = mysqli_fetch_array($result);
            $_SESSION['runnerId'] = $row['runnerId'];
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            $_SESSION['userlevel'] = "runner";
            header("location: orderlist.php");
        } else {
            echoSwal("Wrong username/password", "");
        }

        mysqli_close($conn);
    }
} else {
    echoSwal("You have already logged in.", "document.location.href = 'orderlist.php';");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include('include.php');
    ?>
    <title>Runner Login</title>
</head>

<body>
    <div class="main">
    <?php include('navbar.php'); ?>
        <div class="container">
            <div class="row">
                <div class="col-6 center">
                    <form class="form-group"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <h1 class="center mt-5">Runner Login</h1>
                        <div>
                            <label for="username">Username:</label><br>
                            <input class="form-control" type="text" name="username" id="username">
                        </div>
                        <div>
                            <label for="password">Password:</label><br>
                            <input class="form-control" type="password" name="password" id="password">
                        </div>
                        <br>
                        <button class="form-control btn btn-primary me-2" type="submit">Login</button>
                        <footer>
                            <br>
                            Want to be a runner? <a href="runnerregister.php">Register here</a><br>
                            Other options: <a href="adminlogin.php">Admin Login</a> | <a href="login.php">Customer Login</a>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>