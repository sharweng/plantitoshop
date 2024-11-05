<style>
    :root {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        }
    body {
        height: (100vh - 150px);
        padding-bottom: 90px;
    }
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: purple;
        color: white;
        font-weight: 500;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.2rem;
    }
</style>
<footer>
    <div class="footer">&copy;
        <?php 
            $date = date('Y');
            echo "{$date}";
        ?>
    <span> LMShop. All rights reserved.</span></div>
</footer>
