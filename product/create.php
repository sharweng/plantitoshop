<?php
    session_start();
    include('../includes/config.php');

    if($_SESSION['isAdmin'] == true)
        include('../includes/adminHeader.php');
    else
        include('../includes/header.php');

    if(isset($_POST['back'])){
        $_SESSION['desc'] = "";
        $_SESSION['prc'] = "";
        header("Location: /plantitoshop/product");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
        <?php include '../includes/styles/style.css'; ?>
    </style>
</head>
<body>
    <h1>This is the add item page.</h1>
    <form action="create.php" method="post">
        <button class="add-button" name="back">BACK</button>
    </form>    
    <form method="post" action="store.php" enctype="multipart/form-data">
        <label>Item Name:</label>
        <input class="inputbox" type="text" name="description" value="<?php
            if(isset($_SESSION['desc'])){
                echo $_SESSION['desc'];
            }?>"/>
        <br>
        <label>Price:</label>
        <input class="inputbox" type="text" name="price" value="<?php
            if(isset($_SESSION['prc'])){
                echo $_SESSION['prc'];
            }?>"/>
        <br>
        <label>Quantity:</label> 
        <input class="inputbox" type="number" value="1" placeholder="1" name="quantity" min="1">
        <br>
        <label>Item Picture:</label>
        <input class="inputbox" type="file" name="img_path">
        <br>
        <?php
            if(isset($_SESSION['descError'])){
                echo $_SESSION['descError'];
                unset($_SESSION['descError']);
            }
            if(isset($_SESSION['prcError'])){
                echo $_SESSION['prcError'];
                unset($_SESSION['prcError']);
            }
            if(isset($_SESSION['qtyError'])){
                echo $_SESSION['qtyError'];
                unset($_SESSION['qtyError']);
            }
            if(isset($_SESSION['imgError'])){
                echo $_SESSION['imgError'];
                unset($_SESSION['imgError']);
            }
        ?>
        <button class="button" name="submit">SUBMIT</button>
    </form>
</body>
</html>
<?php
    include('../includes/footer.php');
?>