<?php  
    if(isset($_POST['login'])){
        header("Location: /plantitoshop/user/login.php");
    }  
    if(isset($_POST['logout'])){
        header("Location: /plantitoshop/user/logout.php");
    }
?>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="/plantitoshop" class="green-hover fw-bold">Plantito's Shop</a></h1>
    </div>  
    <nav class="site-navigation">
        <ul class="nav">
        <li><a href="/plantitoshop" class="green-hover">Home</a></li> 
        <li>
          <div class="dropdown">
            <button class="dropbtn">Admin</button>
            <div class="dropdown-content">
                <a href="#">Users</a>
                <a href="/plantitoshop/product/">Products</a>
                <a href="#">Reviews</a>
                <a href="#">Category</a>
                <a href="#">Roles</a>
            </div>
          </div>
        </li>
        <li><a href="#" class="green-hover">About</a></li> 
        <li><a href="#" class="green-hover">Contact</a></li> 
        </ul>
    </nav>
    <div class="site-navigation">
        <ul class="nav">
            <li><a href="#" class="green-hover">Cart</a></li>
            <?php
                if($_SESSION['roleDesc'] == "admin" || $_SESSION['roleDesc'] == "user")
                    echo "<li><div class=\"dropdown\">
                    <button class=\"dropbtn\">Settings</button>
                    <div class=\"dropdown-content\">
                        <a href=\"/plantitoshop/user/profile.php\">Profile</a>
                        <a href=\"/plantitoshop/user/security.php\">Security</a>
                        <a href=\"/plantitoshop/user/logout.php\">Logout</a>
                    </div>
                </div></li>";
                else
                    echo "<li><form action\ method=\"post\"><button class=\"anchor-style green-hover\" name=\"login\">Login</button></form></li>";
            ?>
        </ul>
    </div>
</header>