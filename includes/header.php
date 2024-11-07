<?php
    if(isset($_POST['admin'])){
        $_SESSION['isAdmin'] = true;
        header("Location: /plantitoshop/");
    }
        
?>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="/plantitoshop" class="green-hover">Plantito's Shop</a></h1>
    </div>  
    <nav class="site-navigation">
        <ul class="nav">
        <li><a href="/plantitoshop" class="green-hover">Home</a></li> 
        <li><a href="#" class="green-hover">About</a></li> 
        <li><a href="#" class="green-hover">Contact</a></li> 
        </ul>
    </nav>
    <div class="site-navigation">
        <ul class="nav">
            <li><a href="#" class="green-hover">Cart</a></li>
            <li><form action="" method="post"><button class="anchor-style green-hover" name="admin">Login</button></form></li>
        </ul>
    </div>
</header>