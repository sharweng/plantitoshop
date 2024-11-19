<?php
    session_start();
    include('includes/config.php');
    include('includes/headerBS.php');
    include('includes/notAdminRedirect.php');

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    $sql = "SELECT oi.orderinfo_id, u.user_id, oi.date_placed, os.stat_name, oi.shipping FROM orderinfo oi INNER JOIN user u ON oi.user_id = u.user_id INNER JOIN orderstatus os ON os.stat_id = oi.stat_id WHERE u.user_id = {$_SESSION['user_id']}";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">Orders</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-1">
        <?php include("includes/alert.php"); ?>   
        <table class="table text-center align-middle">
            <tr class="">
                <th>Order #</th>
                <th>Date Placed</th>
                <th>Status</th>
                <th>Total</th>
                <th></th>
            </tr>
            
        <?php 
            while($row = mysqli_fetch_array($result)){
                $total_sql = "SELECT (p.price * ol.quantity) AS total FROM orderline ol INNER JOIN product p ON ol.prod_id = p.prod_id WHERE ol.orderinfo_id = {$row['orderinfo_id']}";
                $total_query = mysqli_query($conn, $total_sql);
                $subtotal = 0;
                while($subtotalresult = mysqli_fetch_array($total_query)){
                    $subtotal = $subtotal + $subtotalresult['total'];
                }
                $grand_total = $row['shipping']+$subtotal;
                echo "<tr class='align-middle'>
                <td class=\"col text-wrap\">{$row['orderinfo_id']}</td>
                <td class=\"col \">{$row['date_placed']}</td>
                <td class=\"col \">{$row['stat_name']}</td>
                <td class=\"col \">&#x20B1;$grand_total</td>
                <td class=\"col \">
                    <div class=\"row d-grid gap-1\">
                        <form action=\"view_order.php\" method=\"post\">
                            <button class=\"btn btn-warning btn-sm w-100\" name=\"orderinfo_id\" value=\"{$row['orderinfo_id']}\">VIEW</button>
                        </form>
                    </div>
                </td>
            </tr>";
            }
        ?>
        </div>
    </div>
</body>
</html>
<?php
    include('includes/footer.php');
?>