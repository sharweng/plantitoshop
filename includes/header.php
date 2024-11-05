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

    .purple-hover:hover{
        cursor: pointer;
        color: #800080;
    }
</style>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="/LMShop" class="purple-hover">Plantito's Shop</a></h1>
    </div>  
    <nav class="site-navigation">
        <ul class="nav">
        <li><a href="/LMShop" class="purple-hover">Home</a></li> 
        <li><a href="/LMShop/items" class="purple-hover">Items</a></li> 
        <li><a href="#" class="purple-hover">About</a></li> 
        <li><a href="#" class="purple-hover">Contact</a></li> 
        <li><a href="#" class="purple-hover">Login</a></li> 
        </ul>
    </nav>
</header>