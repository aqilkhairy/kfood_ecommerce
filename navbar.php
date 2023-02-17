<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="dist/images/logo.png" width="100"/></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if ($_SESSION['userlevel'] == 'admin') { ?>
                    <li><a href="home.php">Product List</a></li>
                    <li><a href="orderlist.php">Order List</a></li>
                    <li><a href="archivedorder.php">Archived Order</a></li>
                <?php } else if ($_SESSION['userlevel'] == 'runner') { ?>
                        <li><a href="orderlist.php">Order List</a></li>
                <?php } else { ?>
                    <li><a href="home.php">Product List</a></li>
                    <li><a href="orderlist.php">My Order</a></li>
                    <li><a href="mycart.php">My Cart</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">Welcome
                        <?php echo $_SESSION["username"]; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>