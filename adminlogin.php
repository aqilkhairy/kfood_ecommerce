<?php
require 'fx.php';

$username = $password = "";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = md5(trim($_POST["password"]));

        $sql = "SELECT * FROM admin WHERE adminUsername = '$username' and adminPass = '$password' ";

        $result = mysqli_query($conn, $sql);

        if (!$result)
            die("Database access failed: " . mysqli_connect_error());

        $rows = mysqli_num_rows($result);
        if ($rows) {
            $row = mysqli_fetch_array($result);
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            $_SESSION['userlevel'] = "admin";
            header("location: home.php");
        } else {
            echo "<script> alert('Oops! Wrong Username & Password'); </script>";
        }

        mysqli_close($conn);
    }
} else {
    echo "<script> alert('You have already logged in.');  document.location.href = 'home.php'; </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include('include.php');
    ?>
    <title>Customer Login</title>
</head>

<body>
    <div class="main">
        <div class="center">
            <form class="form-group" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1 class="center mt-5">Admin Login</h1>
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
                    <br>Other options: <a href="login.php">Customer Login</a>, <a href="runnerlogin.php">Runner Login</a>
                </footer>
            </form>
        </div>
    </div>
</body>

</html>