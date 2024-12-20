<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    if(isset($_GET['email-search']))
        $keyword = strtolower(trim($_GET['email-search']));
    else
        $keyword = "";

    $email_sql = "SELECT user_id, email FROM user";
    if($keyword){
        $email_sql = $email_sql . " WHERE email LIKE '%{$keyword}%'";  
    }
    $email_query = mysqli_query($conn, $email_sql);

    $stat_sql = "SELECT * FROM orderstatus";
    $stat_query = mysqli_query($conn, $stat_sql);

    $oi_sql = "SELECT oi.orderinfo_id, u.email, oi.date_placed, oi.date_shipped, os.stat_id, oi.ship_id, sh.ship_name, sh.ship_price FROM orderinfo oi INNER JOIN user u ON oi.user_id = u.user_id INNER JOIN orderstatus os ON os.stat_id = oi.stat_id 
    INNER JOIN shipping sh ON sh.ship_id = oi.ship_id WHERE oi.orderinfo_id = {$_SESSION['view_id']}";
    $oi_query = mysqli_query($conn, $oi_sql);
    $orderinfo = mysqli_fetch_assoc($oi_query);
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
    <h1 class='text-center p-2 fw-bold'>Edit Order</h1>
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
            <h3 class="text-center fw-bold">Order Details</h3>
            <form action="" method="get" class="row">
                <label for="user_id" class="form-label">Search Email:</label>
                <div class="input-group ">
                    <input type="text" class="form-control" name="email-search">
                    <button class="btn btn-success">Search</button>
                </div>
            </form>
            <form action="update.php" method="post">
                <label for="user_id" class="form-label  my-1">Email:</label>
                <select class="form-select" name="email">
                    <?php
                    while($emails = mysqli_fetch_array($email_query)){
                        if($emails['email'] == $orderinfo['email'])
                            echo "<option selected value=\"{$emails['user_id']}\">{$emails['email']}</option>";
                        else
                            echo "<option value=\"{$emails['user_id']}\">{$emails['email']}</option>";
                    }
                    ?>
                </select>
                <label for="date_placed" class=" my-1 form-label">Date Placed:</label>
                <input type="date" class="form-control" name="date_placed" value="<?php echo $orderinfo['date_placed'] ?>">
                <label class="form-label col-3  my-1">Status:</label>
                <select class="form-select col" name="stat_id">
                    <?php
                        while($stats = mysqli_fetch_array($stat_query)){
                            if($stats['stat_id'] == $orderinfo['stat_id']){
                                echo "<option selected value=\"{$stats['stat_id']}\">{$stats['stat_name']}</option>";
                            }else{
                                echo "<option value=\"{$stats['stat_id']}\">{$stats['stat_name']}</option>";
                            }
                        }
                    ?>
                </select>
                <label class="form-label col-3  my-1">Shipping:</label>
                <select class="form-select col" name="shipping">
                    <?php
                        $ship_sql = "SELECT * FROM shipping";
                        $ship_query = mysqli_query($conn, $ship_sql);
                        while($shipping = mysqli_fetch_array($ship_query)) {
                            if($shipping['ship_id'] == $orderinfo['ship_id'])
                                echo "<option value=\"{$shipping['ship_id']}\" selected>{$shipping['ship_name']}: &#x20B1;{$shipping['ship_price']}</option>";
                            else
                            echo "<option value=\"{$shipping['ship_id']}\">{$shipping['ship_name']}: &#x20B1;{$shipping['ship_price']}</option>";
                           
                        }
                    ?>
                </select>
                <?php 
                    if($orderinfo['date_shipped'] != NULL){
                        echo "<label for=\"date_shipped\" class=\" my-1 form-label\">Date Shipped:</label>
                <input type=\"date\" class=\"form-control\" name=\"date_shipped\" value=\"{$orderinfo['date_shipped']}\">";
                    }
                ?>
                <button type="submit" class="col btn btn-success mt-3 w-100" name="updateOD">UPDATE ORDER INFO</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>
