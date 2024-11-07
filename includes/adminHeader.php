<?php
    if(isset($_POST['admin'])){
        $_SESSION['isAdmin'] = false;
        header("Location: /plantitoshop/");
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
            <li><form action="" method="post"><button class="anchor-style green-hover" name="admin">Logout</button></form></li>
        </ul>
    </div>
</header>