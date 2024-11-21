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
        $sql_order = "INSERT INTO orderinfo (user_id, date_placed, stat_id, shipping) VALUES (?, NOW(), ?, ?)";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("iii", $user_id, $status, $shipping);
        $stmt_order->execute();

        $orderinfo_id = $conn->insert_id; // Get generated order ID

        $_SESSION['fromAdmin'] = false;
        $_SESSION['view_id'] = $orderinfo_id;
        // Insert each product into `orderline`
        $sql_orderline = "INSERT INTO orderline (orderinfo_id, prod_id, quantity) VALUES (?, ?, ?)";
        $stmt_details = $conn->prepare($sql_orderline);

        foreach ($_SESSION["cart_products"] as $product) {
            if($product['prod_qty'] > $product['stk_qty']){
                $_SESSION['message'] = "Invalid quantity: The quantity you selected is higher than the available stock.";
                header("Location: view_cart.php");
                exit();
            }
            
            $stmt_details->bind_param(
                "iii",
                $orderinfo_id,
                $product["prod_id"],
                $product["prod_qty"]
            );
            if($product['prod_qty'] == $product['stk_qty']){
                $stock = $product['prod_qty']+100;
                $sql_update_stock = "UPDATE stock SET quantity = $stock WHERE prod_id = ?";
                $stmt_update_stock = $conn->prepare($sql_update_stock);
                $stmt_update_stock->bind_param("i", $product['prod_id']);
                $stmt_update_stock->execute();
            }
            $stmt_details->execute();

            
        }


        $conn->commit();
        
        unset($_SESSION["cart_products"]); // Clear cart after checkout
        $_SESSION['success'] = "Order placed successfully! Thank you for your purchase.";
        header("Location: send_email.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error processing order: " . $e->getMessage();
        header("Location: view_cart.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Your cart is empty!";
    header("Location: view_cart.php");
    exit();
}
?>