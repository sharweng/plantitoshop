<?php
    if(isset($_POST['login'])){
        header("Location: /plantitoshop/user/login.php");
    }  
?>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="/plantitoshop" class="green-hover fw-bold">Plantito's Shop</a></h1>
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
            <li><form action="" method="post"><button class="anchor-style green-hover" name="login">Login</button></form></li>
        </ul>
    </div>
</header>