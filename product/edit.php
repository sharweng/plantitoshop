<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

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
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include '../includes/styles/style.css'; ?>
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">Edit Product</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-2 d-flex align-items-center justify-content-start">
                <form action="" method="post">
                    <button class="btn btn-success" name="back">BACK</button>
                </form> 
                </div>
                <div class="col-4 d-flex align-items-center justify-content-end">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
        <form method="post" action="update.php" enctype="multipart/form-data">
            <label class="form-label">Product Name:</label>
            <input class="form-control" type="text" name="description"  value="<?php
                echo $row['description'];
            ?>"/>
            <label class="form-text text-danger"><?php
                if(isset($_SESSION['descError'])){
                    echo $_SESSION['descError'];
                    unset($_SESSION['descError']);
                }
            ?></label><br>
            <label class="form-label">Category:</label>
            <select class="form-select" name="category" >  
                <?php 
                while($categories = mysqli_fetch_array($result)){
                    if($categories['cat_id'] == $row['cat_id'])
                        echo "<option selected value=\"{$categories['cat_id']}\">{$categories['description']}</option>";
                    else
                        echo "<option value=\"{$categories['cat_id']}\">{$categories['description']}</option>";
                }
            ?>
            </select>
            <label class="form-text"></label><br>
            <label class="form-label">Price:</label>
            <input class="form-control" type="text" name="price"  value="<?php
                echo $row['price'];
            ?>"/>
            <label class="form-text text-danger"><?php
                if(isset($_SESSION['prcError'])){
                    echo $_SESSION['prcError'];
                    unset($_SESSION['prcError']);
                }
            ?></label><br>
            <label class="form-label">Quantity:</label> 
            <input class="form-control" type="number" placeholder="1" name="quantity" min="1"  value="<?php
                echo $row['quantity'];
            ?>">
            <label class="form-text text-danger"><?php
                if(isset($_SESSION['qtyError'])){
                    echo $_SESSION['qtyError'];
                    unset($_SESSION['qtyError']);
                }
            ?></label><br>
            <label class="form-label">Item Picture:</label>
            <input class="form-control" type="file" name="img_path[]" multiple accept="image/*">
            <label class="form-text text-danger"></label><br>
            <label class="form-text">No Upload = Image don't update</label>
            <button class="btn btn-success w-100 form-btn my-2" name="submit">SUBMIT</button>
        </form>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>