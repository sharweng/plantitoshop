<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    $_SESSION['fromAdmin'] = true;

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    $sql = "SELECT oi.orderinfo_id, CONCAT(u.lname,', ', u.fname) AS uname, u.email, oi.date_placed, os.stat_name, oi.shipping FROM orderinfo oi INNER JOIN user u ON oi.user_id = u.user_id INNER JOIN orderstatus os ON os.stat_id = oi.stat_id";
    if(isset($_POST['view_id'])){
        $view_id = $_POST['view_id'];
        $_SESSION['view_id'] = $view_id;
    }
    $sql = $sql . " WHERE oi.orderinfo_id = {$_SESSION['view_id']}";
    
    $product_sql = "SELECT ol.orderinfo_id, ol.prod_id, p.description, ol.quantity FROM orderline ol INNER JOIN product p ON ol.prod_id = p.prod_id WHERE ol.orderinfo_id = {$_SESSION['view_id']}";
    if($keyword){
        $product_sql = $product_sql . " AND p.description LIKE '%{$keyword}%'";  
    }

    $prod_query = mysqli_query($conn, $product_sql);
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
        <?php include('../includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">View Order</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/order/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
                <form action="" method="get" class="d-inline-block">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search">
                        <button class="btn btn-success">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-1">
            <?php include("../includes/alert.php"); ?>   
            <table class="table text-center align-middle">
                <tr class="">
                    <th>id</th>
                    <th>Name</th>
                    <th>Date Placed</th>
                    <th>Status</th>
                    <th>Ship Fee</th>
                    <th></th>
                </tr>
                
                <?php 
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr class='align-middle'>
                        <td class=\"col \">{$row['orderinfo_id']}</td>
                        <td class=\"col text-wrap\">{$row['uname']}</td>
                        <td class=\"col \">{$row['date_placed']}</td>
                        <td class=\"col \">{$row['stat_name']}</td>
                        <td class=\"col \">{$row['shipping']}</td>
                        <td class=\"col \">
                            <div class=\"row\">
                                <form action=\"editOD.php\" method=\"post\">
                                    <button class=\"btn btn-warning btn-sm w-100 px-4\" name=\"update_id\" value=\"{$row['orderinfo_id']}\">EDIT</button>
                                </form>
                            </div>
                        </td>
                    </tr>";
                    }
                ?>
                <tr>
                    <td colspan="6">
                        <form action="/plantitoshop/send_email.php" method="post">
                            <input type="number" name="orderinfo_id" hidden value="<?php $email = mysqli_fetch_assoc($result); echo $email['orderinfo_id']; ?>">
                            <button class="btn btn-secondary w-100 btn-sm" name="send_order_receipt" value="<?php
                            $email = mysqli_fetch_assoc($result);
                             echo $email['email'] ?>">SEND ORDER RECEIPT</button>
                        </form>

                    </td>
                </tr>
            </table>
            <table class="table text-center align-middle">
                <tr>
                    <td colspan="4" class="fw-bold h3">ORDERS</td>
                </tr>
                <tr>
                    <td colspan="4" class="">
                        <a href="/plantitoshop/order/createOI.php" class="btn btn-success w-100">ADD PRODUCT</a>
                    </td>
                </tr>
                <tr>
                    <th>prodID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
                <?php
                    while($products = mysqli_fetch_array($prod_query)){
                        echo "<tr class='align-middle'>
                                <td class=\"col \">{$products['prod_id']}</td>
                                <td class=\"col text-wrap\">{$products['description']}</td>
                                <td class=\"col \">{$products['quantity']}</td>
                                <td class=\"col \">
                                    <div class=\"row d-grid gap-1\">
                                        <form action=\"editOI.php\" method=\"post\">
                                            <button class=\"btn btn-warning btn-sm w-100\" name=\"prod_id\" value=\"{$products['prod_id']}\">EDIT</button>
                                        </form>
                                        <div class=\"dropdown d-block\">
                                            <button class=\"btn btn-danger btn-sm w-100 dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                                DELETE
                                            </button>
                                            <ul class=\"dropdown-menu\">
                                                <form action=\"delete.php\" method=\"post\">
                                                    <input type=\"hidden\" name=\"oi_id\" value=\"{$products['orderinfo_id']}\"/>
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"orderline\" value=\"{$products['prod_id']}\">YES</button>
                                                </form>
                                                <form action=\"\" method=\"post\">
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"no\" value=\"{$products['prod_id']}\">NO</button>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
                    }
                ?>
            </table>
            
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>