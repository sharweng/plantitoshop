<?php
session_start();
include('includes/config.php'); // Database connection
include('includes/notUserRedirect.php');
include('includes/headerBS.php');

if(isset($_POST['review'])){
    $_SESSION['prod_id'] = $_POST['review'];
    header("Location: view_product.php");
}  

// Ensure an order ID is provided
if (!isset($_POST['orderinfo_id']) || empty($_POST['orderinfo_id'])) {
    echo "<div class='container mt-5'><h3>Invalid Order ID</h3></div>";
    exit;
}

$orderinfo_id = $_POST['orderinfo_id'];

// Fetch order details from the view
$order_query = "SELECT * FROM order_transaction_details WHERE orderinfo_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $orderinfo_id);
$stmt->execute();
$order_result = $stmt->get_result();

// Fetch general order info (assuming all rows have the same order info)
$order_general = $order_result->fetch_assoc();
$order_result->data_seek(0); // Reset result pointer for detailed items

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
    </style>
</head>
<body>
<h1 class="text-center p-2 fw-bold">Order Details</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-2 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/orders.php">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-10 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2">
            <?php if ($order_result->num_rows != 0) { ?>
            <div class="card mt-3">
                <div class="card-header">
                    <strong>Order #<?php echo $order_general['orderinfo_id']; ?></strong>
                </div>
                <div class="card-body">
                    <p><strong>Order Date:</strong> <?php echo $order_general['date_placed']; ?></p>
                    <p><strong>Customer:</strong> <?php echo $order_general['customer_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order_general['customer_email']; ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo $order_general['shipping_address']; ?></p>
                    <p><strong>Order Status:</strong> <?php echo $order_general['order_status']; ?></p>
                    <?php 
                        if($order_general['date_shipped'] != NULL)
                            echo "<p><strong>Order Date Shipped:</strong> {$order_general['date_shipped']}</p>";
                    ?>

                </div>
            </div>
            <h3 class="mt-3 fw-bold text-center">Ordered Products</h3>
            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <?php
                            $deliveredsql = "SELECT oi.stat_id, u.user_id FROM user u INNER JOIN orderinfo oi ON u.user_id = oi.user_id WHERE oi.stat_id = 2 AND u.user_id = {$_SESSION['user_id']}";
                            $deliveredquery = mysqli_query($conn, $deliveredsql);

                            if($deliveredquery->num_rows != 0){
                                echo "<th></th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    while ($item = $order_result->fetch_assoc()): 
                        $subtotal += $item['total_price'];
                        $grand_total_calculated = $subtotal + $order_general['shipping_fee'];
                    ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td>&#x20B1;<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td><?php echo $item['quantity_ordered']; ?></td>
                            <td>&#x20B1;<?php echo number_format($item['total_price'], 2); ?></td>
                            <?php
                                $deliveredsql = "SELECT oi.stat_id, u.user_id FROM user u INNER JOIN orderinfo oi ON u.user_id = oi.user_id WHERE oi.stat_id = 2 AND u.user_id = {$_SESSION['user_id']}";
                                $deliveredquery = mysqli_query($conn, $deliveredsql);

                                if($deliveredquery->num_rows != 0){
                                    echo "<td class=\"col \">
                                        <form action=\"\" method=\"post\">
                                            <button class=\"btn btn-warning btn-sm w-100\" name=\"review\" value='{$item['product_id']}'><i class=\"bi bi-pencil-square\"></i></button>
                                        </form>
                                    </td>";
                                }
                            ?>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3"><strong>Subtotal</strong></td>
                        <td>&#x20B1;<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Shipping Fee</strong></td>
                        <td>&#x20B1;<?php echo number_format($order_general['shipping_fee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td>&#x20B1;<?php echo number_format($grand_total_calculated, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php }else{
                    echo "<p class=\"text-center mt-2 fw-bold\">No products found. Cannot view order.</p>";
                } 
            ?>
        </div>
    </div>
</body>
</html>
<?php include('includes/footer.php'); ?>

