<?php
session_start();
include('includes/config.php');

if (isset($_SESSION["cart_products"]) && !empty($_SESSION["cart_products"])) {
    $order_total = 0;
    $status = 1; // Default order placed status (Pending, etc.)
    $user_id = $_SESSION['user_id']; // Ensure this exists

    $shipping = ($_POST['shipping'] == 1) ? 40 : 120;

    $conn->begin_transaction();

    try {
        // Insert into `orderinfo`
        $sql_order = "INSERT INTO orderinfo (user_id, date_placed, order_status, shipping) VALUES (?, NOW(), ?, ?)";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("iii", $user_id, $status, $shipping);
        $stmt_order->execute();

        $orderinfo_id = $conn->insert_id; // Get generated order ID

        // Insert each product into `orderline`
        $sql_orderline = "INSERT INTO orderline (orderinfo_id, prod_id, quantity) VALUES (?, ?, ?)";
        $stmt_details = $conn->prepare($sql_orderline);

        foreach ($_SESSION["cart_products"] as $product) {
            $stmt_details->bind_param(
                "iii",
                $orderinfo_id,
                $product["prod_id"],
                $product["prod_qty"]
            );
            $stmt_details->execute();
        }

        $conn->commit();
        unset($_SESSION["cart_products"]); // Clear cart after checkout
        echo "<p>Order placed successfully! Your Order ID is: $orderinfo_id</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    echo "<p>Your cart is empty!</p>";
}
?>
<a href="index.php" class="btn btn-primary">Back to Home</a>