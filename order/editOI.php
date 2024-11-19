<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    $select_sql = "SELECT stat_id FROM orderinfo WHERE orderinfo_id = {$_SESSION['view_id']}";
    $select_query = mysqli_query($conn, $select_sql);
    $select = mysqli_fetch_assoc($select_query);

    if($select['stat_id'] == 2){
        $_SESSION['message'] = "Cannot edit product. Order was already delivered.";
        header('Location: /plantitoshop/order/order_view.php');
        exit();
    }elseif($select['stat_id'] == 3){
        $_SESSION['message'] = "Cannot edit product. Order was already cancelled.";
        header('Location: /plantitoshop/order/order_view.php');
        exit();
    }

    if(isset($_GET['prod-search']))
        $keyword = strtolower(trim($_GET['prod-search']));
    else
        $keyword = "";

    $prod_sql = "SELECT p.prod_id, p.description, c.description as cat, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id";
    if($keyword){
        $prod_sql = $prod_sql . " WHERE p.description LIKE '%{$keyword}%'";  
    }
    $prod_query = mysqli_query($conn, $prod_sql);

    if (isset($_POST['prod_id'])){
        $prod_id = $_POST['prod_id'];
        $_SESSION['editprod_id'] = $prod_id;
    }

    $ol_sql = "SELECT orderinfo_id, prod_id, quantity FROM orderline WHERE orderinfo_id = {$_SESSION['view_id']} AND prod_id = {$_SESSION['editprod_id']}";
    $ol_query = mysqli_query($conn, $ol_sql);
    $orderline = mysqli_fetch_assoc($ol_query);

    $select_sql = "SELECT ol.orderinfo_id, ol.prod_id, p.description, ol.quantity FROM orderline ol INNER JOIN product p ON ol.prod_id = p.prod_id WHERE ol.orderinfo_id = {$_SESSION['view_id']}";
    $select_query = mysqli_query($conn, $select_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<h1 class="text-center p-2 fw-bold">Edit Order</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-2 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/order/order_view.php">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-10 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
            <?php include("../includes/alert.php"); ?>
            <h3 class="text-center fw-bold">Order Products</h3>
            <form action="" method="get" class="row">
                <label for="user_id" class="form-label">Search Product:</label>
                <div class="input-group ">
                    <input type="text" class="form-control" name="prod-search">
                    <button class="btn btn-success">Search</button>
                </div>
                <label class="form-text"></label>
            </form>
            <form action="update.php" method="post">
                <label for="user_id" class="form-label">Product:</label>
                <select class="form-select" name="prod">
                    <?php
                        $selected_products = [];
                        while($select = mysqli_fetch_array($select_query)){
                            if($select['prod_id'] != $orderline['prod_id'])
                                $selected_products[] = $select['prod_id'];
                        }
                    
                        while($products = mysqli_fetch_array($prod_query)){
                        
                            if(in_array($products['prod_id'], $selected_products)){
                                continue;
                            }

                            if($products['prod_id'] == $orderline['prod_id']){
                                echo "<option selected value=\"{$products['prod_id']}\">{$products['description']} / {$products['cat']} / &#x20B1;{$products['price']} / {$products['quantity']}</option>";
                            } else {
                                echo "<option value=\"{$products['prod_id']}\">{$products['description']} / {$products['cat']} / &#x20B1;{$products['price']} / {$products['quantity']}</option>";
                            }
                        }
                    ?>
                </select>
                <label class="form-text"></label><br>
                <label class="form-label">Quantity:</label> 
                <input class="form-control" type="number" hidden value="<?php echo $orderline['quantity'] ?>" placeholder="1" name="bfr_qty" min="1">
                <input class="form-control" type="number" value="<?php echo $orderline['quantity'] ?>" placeholder="1" name="quantity" min="1">
                <button type="submit" class="col btn btn-success mt-3 w-100" name="updateOI">UPDATE PRODUCT TO ORDER</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>
