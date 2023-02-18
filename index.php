<?php
require 'fx.php';

session_start();
?>


<!DOCTYPE html>

<head>
    <?php
    include('include.php');
    ?>
    <title>Oneul Korean Food</title>
</head>

<body>
    <div class="main" >
    <?php include('navbar.php'); ?>
        <!-- MAIN BODY STARTS -->
        <div class="container">
            <h1 class="center mt-5 mb-1">Welcome to Oneul Korean Food</h1>
            <p class="center">Halal korean-like cafe in Kota Bharu, Kelantan. Order and enjoy our korean food anytime, anywhere! </p>
            <?php if(isset($_SESSION["loggedin"])) { 
                } else { ?>
            <p class="center mb-4"><a href="register.php">Register</a>&nbsp;or&nbsp;<a href="login.php">Login</a>&nbsp;to order for delivery.</p>
            <?php } ?>
            <br>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">About Us</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.895143953071!2d102.2591653!3d6.144784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31b6b109e06d42ff%3A0x1004dbe7b37f6b69!2sNAMI%20KFOOD%20Korean%20Food!5e0!3m2!1sen!2smy!4v1676703476719!5m2!1sen!2smy" width="350" height="215" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div> 
                                <div class="col-md-6">
                                    <b>Location:</b><br> 
                                    <span class="glyphicon glyphicon-pushpin"></span> 
                                    No. 4 Jalan Raja Perempuan Zainab II, Kubang Kerian, Kota Bharu, Kelantan<br>
                                    <br>
                                    <b>Open hours:</b><br> 
                                    <span class="glyphicon glyphicon-time"></span> 
                                    Saturday - Sunday (11.00am - 6.30pm)<br>
                                    <br>
                                    <span class="glyphicon glyphicon-cutlery"></span> 
                                    <b>Halal Certified</b><br>
                                    <br>
                                    <b>Delivery:</b><br>
                                    <span class="glyphicon glyphicon-info-sign"></span> 
                                    All Kelantan area up to Besut
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Contact</div>
                        <div class="panel-body">
                            <b>Phone No.:</b><br> 
                            <span class="glyphicon glyphicon-earphone"></span> 
                            010-2132795<br>
                            <br>
                            <b>Whatsapp Link:</b><br> 
                            <span class="glyphicon glyphicon-comment"></span> 
                            <a href="https://wa.link/j0omhj">Chat on Whatsapp</a><br>
                            <br>
                            <b>Email:</b><br> 
                            <span class="glyphicon glyphicon-envelope"></span> 
                            <a href="mailto:namifood81@gmail.com">namifood81@gmail.com</a><br>
                            <br>
                            <b>Instagram:</b><br> 
                            <span class="glyphicon glyphicon-camera"></span> 
                            <a href="https://www.instagram.com/kelantankoreanfood/" target="_blank">@kelantankoreanfood</a><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN BODY ENDS -->
    </div>
</body>
</html>