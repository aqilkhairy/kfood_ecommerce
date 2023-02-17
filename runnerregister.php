<?php
require 'fx.php';

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username.";
        } else {
            // Prepare a select statement 
            $sql = "SELECT runnerUsername FROM runner WHERE runnerUsername = ?";
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
                    echoSwal("Database query failed", "");
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
            $sql = "INSERT INTO runner (runnerUsername, runnerPass) VALUES (?, ?) ";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                $param_username = $_POST["username"];
                $param_password = md5($password);

                if (mysqli_stmt_execute($stmt)) {
                    echoSwal("Registration success.", "document.location.href = 'login.php';");
                } else {
                    echoSwal("Database query failed", "");
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $err_string = "";
            if (!empty($username_err)) {
                $err_string .= $username_err . "\\n";
            }
            if (!empty($password_err)) {
                $err_string .= $password_err . "\\n";
            }
            if (!empty($confirm_password_err)) {
                $err_string .= $confirm_password_err . "\\n";
            }
            echoSwal($err_string, "");
        }
        mysqli_close($conn);
    }
} else {
    echoSwal("You have already logged in.", "document.location.href = 'home.php';");
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
        <div class="container">
            <div class="row">
                <div class="col-6 center">
                    <img class="center-image" src="dist/images/logo.png" width="200"/>
                </div>
                <div class="col-6 center">
                    <form class="form-group"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <h1 class="center mt-5">Runner Sign Up</h1>
                        <div>
                            <label for="username">Username:</label><br>
                            <input class="form-control" type="text" name="username" id="username">
                        </div>
                        <div>
                            <label for="password">Password:</label><br>
                            <input class="form-control" type="password" name="password" id="password">
                        </div>
                        <div>
                            <label for="password2">Password Again:</label><br>
                            <input class="form-control" type="password" name="password2" id="password2">
                        </div>
                        <br>
                        <button class="form-control btn btn-warning me-2" type="submit">Register</button>
                        <br>
                        <footer>Already a runner? <a href="runnerlogin.php">Login here</a></footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>