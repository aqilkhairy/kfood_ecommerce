<?php
require 'fx.php';

$username = $password = "";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = md5(trim($_POST["password"]));

        $sql = "SELECT * FROM customer WHERE custUsername = '$username' and custPass = '$password' ";

        $result = mysqli_query($conn, $sql);

        if (!$result)
            die("Database access failed: " . mysqli_connect_error());

        $rows = mysqli_num_rows($result);
        if ($rows) {
            $row = mysqli_fetch_array($result);
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            $_SESSION['userlevel'] = "user";
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
    <title>Login</title>
</head>

<body>
    <div class="main">
        <div class="center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1 class="center mt-5">Login</h1>
                <div>
                    <label for="username">Username:</label><br>
                    <input type="text" name="username" id="username">
                </div>
                <div>
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" id="password">
                </div>
                <br>
                <button class="btn btn-outline-dark me-2" type="submit">Login</button>
                <footer>Not a member? <a href="register.php">Register here</a></footer>
            </form>
        </div>
    </div>
</body>

</html>