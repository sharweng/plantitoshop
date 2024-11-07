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

    if(isset($_POST['update_id'])){
        $_SESSION['u_id'] = $_POST['update_id'];
        $u_id = $_POST['update_id'];
    }else{
        $u_id = $_SESSION['u_id'];
    }

    $sql = "SELECT * FROM category";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT p.prod_id, p.description, p.price, c.cat_id, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id  WHERE p.prod_id = {$u_id}";
    $result2 = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include '../includes/styles/style.css'; ?>
    </style>
</head>
<body>
    <h1>This is the edit product page.</h1>
    <form action="create.php" method="post">
        <button class="add-button" name="back">BACK</button>
    </form>    
    <form method="post" action="update.php" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input class="inputbox" type="text" name="description"  value="<?php
            echo $row['description'];
        ?>" required/>
        <br>
        <label>Category:</label>
        <select name="category" >  
            <?php 
            while($categories = mysqli_fetch_array($result)){
                if($categories['cat_id'] == $row['cat_id'])
                    echo "<option selected value=\"{$categories['cat_id']}\">{$categories['description']}</option>";
                else
                    echo "<option value=\"{$categories['cat_id']}\">{$categories['description']}</option>";
            }
        ?>
        </select>
        <br>
        <label>Price:</label>
        <input class="inputbox" type="text" name="price"  value="<?php
            echo $row['price'];
        ?>" required/>
        <br>
        <label>Quantity:</label> 
        <input class="inputbox" type="number" placeholder="1" name="quantity" min="1"  value="<?php
            echo $row['quantity'];
        ?>" required>
        <br>
        <label>Item Picture:</label>
        <input class="inputbox" type="file" name="img_path[]" multiple accept="image/*">
        <br>
        <label>No Upload = Image don't update</label>
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
        ?>
        <button class="button" name="submit">SUBMIT</button>
    </form>
</body>
</html>
<?php
    include('../includes/footer.php');
?>