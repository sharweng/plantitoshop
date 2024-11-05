<?php
    if(isset($_POST['admin'])){
        $_SESSION['isAdmin'] = false;
        header("Location: /plantitoshop/");
    }      
?>
<link rel="stylesheet" href="styles/style.css">
<style type="text/css">
    body {
        font-family: Helvetica;
        margin: 0;
    }
    a {
        text-decoration: none;
        color: #000;
    }
    .site-header { 
        background-color: white;
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        border-bottom: 1px solid #ccc;
        padding: .5em 1em;
        display: flex;
        justify-content: space-between;
        z-index: 10;
    }

    .site-identity h1 {
        font-size: 1.5em;
        margin: .6em 0;
        display: inline-block;
    }

    .site-navigation ul, 
    .site-navigation li {
        margin: 0; 
        padding: 0;
    }

    .site-navigation li {
        display: inline-block;
        margin: 1.4em 1em 1em 1em;
    }

    /* Style The Dropdown Button */
    .dropbtn {
        color: black;
        font-size: 16px;
        border: none;
        cursor: pointer;
        background-color: white;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        left: -10;
        background-color: #f9f9f9;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        min-width: 100;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: #f1f1f1;
        color: #337357;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        color: #337357;
    }

    .green-hover:hover{
        cursor: pointer;
        color: #337357;
    }
</style>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="/plantitoshop" class="green-hover">Plantito's Shop</a></h1>
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