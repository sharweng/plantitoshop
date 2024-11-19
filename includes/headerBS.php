<?php
    if(isset($_POST['login'])){
        header("Location: /plantitoshop/user/login.php");
    }  
    if(isset($_POST['logout'])){
        header("Location: /plantitoshop/user/logout.php");
    }
?>
<nav class="navbar navbar-expand-sm bg-body-tertiary site-header border-bottom">
    <div class="container-fluid" style="max-width: 1000px;">
        <a class="navbar-brand fw-bold green-hover" href="/plantitoshop/">Plantito's Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link green-hover" href="/plantitoshop/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link green-hover" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link green-hover" href="#">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link green-hover position-relative" href="/plantitoshop/view_cart.php">Cart
                        <?php 
                            if(isset($_SESSION['cart_products'])){
                                $product_count = count($_SESSION["cart_products"]);
                                if($product_count > 0){
                                    echo "<span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger\">
                                        $product_count
                                    </span>";
                                }
                            }
                        ?>
                    </a>
                </li>
                <?php
                    if(!isset($_SESSION['roleDesc'])){
                        $_SESSION['roleDesc'] = "";
                    }
                    if($_SESSION['roleDesc'] == "admin"){
                        echo "<li class=\"nav-item dropdown\">
                            <a class=\"nav-link dropdown-toggle green-hover\" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                Admin
                            </a>
                            <ul class=\"dropdown-menu\">
                                <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/\">Users</a></li>
                                <li><a class=\"dropdown-item\" href=\"/plantitoshop/order/\">Orders</a></li>
                                <li><a class=\"dropdown-item\" href=\"/plantitoshop/product/\">Products</a></li>
                                <li><a class=\"dropdown-item\" href=\"#\">Reviews</a></li>
                            </ul>
                        </li>";
                    }
                    if($_SESSION['roleDesc'] == "admin" || $_SESSION['roleDesc'] == "user"){
                        $sql = "SELECT lname, fname, pfp_path FROM user WHERE user_id = {$_SESSION['user_id']}";
                        $DBpath = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_array($DBpath)){
                            $settingFname = $row['fname'];
                            $settingLname = $row['lname'];
                            $settingPath = "/plantitoshop/user/".$row['pfp_path'];
                        }
                        echo "<li class=\"nav-item dropdown\">
                                <a class=\"nav-link dropdown-toggle green-hover\" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                    Settings
                                </a>
                                <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-sm-start\">
                                    <li class=\"text-center p-2\">
                                        <img src=\"$settingPath\" class=\"rounded-circle\" style=\"width: 50px; height: 50px; object-fit: cover;\">
                                        <div class=\"fw-bold text-wrap\">$settingFname $settingLname</div>
                                    </li>
                                    <li><hr class=\"dropdown-divider\"></li>
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/profile.php\">Profile</a></li>
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/security.php\">Security</a></li>
                                    <li><hr class=\"dropdown-divider\"></li>
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/logout.php\">Logout</a></li>
                                </ul>
                            </li>";
                    }else{
                        echo "<li class=\"nav-item\">
                                <form action=\"\" method=\"post\"><button class=\"nav-link green-hover\" name=\"login\">Login</button></form>
                            </li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>