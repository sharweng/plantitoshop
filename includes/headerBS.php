<?php
    if(isset($_POST['login'])){
        header("Location: /plantitoshop/user/login.php");
    }  
    if(isset($_POST['logout'])){
        header("Location: /plantitoshop/user/logout.php");
    }
?>
<nav class="navbar navbar-expand-sm bg-body-tertiary site-header">
    <div class="container-fluid" style="max-width: 1000px;">
        <a class="navbar-brand fw-bold" href="/plantitoshop/">Plantito's Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/plantitoshop/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Cart</a>
                </li>
                <?php
                    if($_SESSION['roleDesc'] == "admin"){
                        echo "<li class=\"nav-item dropdown\">
                            <a class=\"nav-link dropdown-toggle active\" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                Admin
                            </a>
                            <ul class=\"dropdown-menu\">
                                <li><a class=\"dropdown-item\" href=\"#\">Users</a></li>
                                <li><a class=\"dropdown-item\" href=\"/plantitoshop/product/\">Products</a></li>
                                <li><a class=\"dropdown-item\" href=\"#\">Reviews</a></li>
                                <li><a class=\"dropdown-item\" href=\"#\">Categories</a></li>
                                <li><a class=\"dropdown-item\" href=\"#\">Roles</a></li>
                            </ul>
                        </li>";
                    }
                    if($_SESSION['roleDesc'] == "admin" || $_SESSION['roleDesc'] == "user")
                        echo "<li class=\"nav-item dropdown\">
                                <a class=\"nav-link dropdown-toggle active\" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                    Settings
                                </a>
                                <ul class=\"dropdown-menu dropdown-menu-end dropdown-menu-sm-start\">
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/profile.php\">Profile</a></li>
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/security.php\">Security</a></li>
                                    <li><hr class=\"dropdown-divider\"></li>
                                    <li><a class=\"dropdown-item\" href=\"/plantitoshop/user/logout.php\">Logout</a></li>
                                </ul>
                            </li>";
                    else{
                        echo "<li class=\"nav-item\">
                                <form action=\"\" method=\"post\"><button class=\"nav-link active\" name=\"login\">Login</button></form>
                            </li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>