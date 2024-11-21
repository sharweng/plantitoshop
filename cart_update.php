<?php
session_start();
include('includes/config.php');
include('includes/notUserRedirect.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST["type"]) && $_POST["type"] == 'add' && $_POST["quantity"] > 0) {
        $prod_id = $_POST["prod_id"];
        $prod_qty = $_POST["quantity"];
        $stk_qty = $_POST['stock'];

        // Fetch product details
        $sql = "SELECT prod_id, description, price FROM product WHERE prod_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $prod_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $new_product = [
                'prod_id' => $product['prod_id'],
                'prod_name' => $product['description'],
                'prod_price' => $product['price'],
                'prod_qty' => $prod_qty,
                'stk_qty' => $stk_qty
            ];

            if (isset($_SESSION["cart_products"][$prod_id])) {
                $_SESSION["cart_products"][$prod_id]["prod_qty"] += $prod_qty;
            } else {
                $_SESSION["cart_products"][$prod_id] = $new_product;
            }
        }
    }

    if (isset($_POST["product_qty"]) || isset($_POST["remove_code"])) {
        if (isset($_POST["product_qty"])) {
            foreach ($_POST["product_qty"] as $key => $value) {
                if (is_numeric($value) && $value > 0) {
                    $_SESSION["cart_products"][$key]["prod_qty"] = $value;
                }
            }
        }

        if (isset($_POST["remove_code"])) {
            foreach ($_POST["remove_code"] as $key) {
                unset($_SESSION["cart_products"][$key]);
            }
        }
    }
}

if(isset($_POST['add_cart']))
    header('Location: index.php');
else
    header('Location: view_cart.php');
exit;
?>