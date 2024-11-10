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

    $sql = "SELECT * FROM category";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include '../includes/styles/style.css'; ?>
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">This is the add product page.</h1>
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
        <div class="container inner-box border border-success border-2 py-2">
            <form method="post" action="store.php" enctype="multipart/form-data">
                <label class="form-label">Product Name:</label>
                <input class="form-control" type="text" name="description" value="<?php
                    if(isset($_SESSION['desc'])){
                        echo $_SESSION['desc'];
                    }?>"/>
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['descError'])){
                        echo $_SESSION['descError'];
                        unset($_SESSION['descError']);
                    }
                ?></label><br>
                <label class="form-label">Category:</label>
                <select class="form-select" name="category">  
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
                <input class="form-control" type="text" name="price" value="<?php
                    if(isset($_SESSION['prc'])){
                        echo $_SESSION['prc'];
                    }?>"/>
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['prcError'])){
                        echo $_SESSION['prcError'];
                        unset($_SESSION['prcError']);
                    }
                ?></label><br>
                <label class="form-label">Quantity:</label> 
                <input class="form-control" type="number" value="1" placeholder="1" name="quantity" min="1">
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['qtyError'])){
                        echo $_SESSION['qtyError'];
                        unset($_SESSION['qtyError']);
                    }
                ?></label><br>
                <label class="form-label">Item Picture:</label>
                <input class="form-control" type="file" name="img_path[]" multiple accept="image/*">
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['imgError'])){
                        echo $_SESSION['imgError'];
                        unset($_SESSION['imgError']);
                    }
                ?></label><br>
                <button class="btn btn-success w-100 form-btn my-2" name="submit">SUBMIT</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>