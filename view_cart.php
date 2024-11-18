<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="text-center p-2 fw-bold">Your Cart</h1>

    <div class="container">
        <?php
        if (isset($_SESSION["cart_products"]) && !empty($_SESSION["cart_products"])) {
            echo "<form action='cart_update.php' method='POST'>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Remove</th></tr>";

            $grand_total = 0;

            foreach ($_SESSION["cart_products"] as $product_id => $product) {
                $product_total = $product["prod_price"] * $product["prod_qty"];
                $grand_total += $product_total;

                echo "<tr>";
                echo "<td>{$product['prod_name']}</td>";
                echo "<td>&#x20B1;{$product['prod_price']}</td>";
                echo "<td><input type='number' name='product_qty[{$product_id}]' value='{$product['prod_qty']}' min='1' class='form-control'></td>";
                echo "<td>&#x20B1;{$product_total}</td>";
                echo "<td><input type='checkbox' class='form-check-input' name='remove_code[]' value='{$product_id}'></td>";
                echo "</tr>";
            }
            echo "<tr><td colspan='3'><strong>Grand Total</strong></td><td colspan='2'>&#x20B1;{$grand_total}</td></tr>";
            echo "</table>";
            echo "<button type='submit' class='btn btn-success'>Update Cart</button>";
            echo "</form>";
        } else {
            echo "<p>Your cart is empty!</p>";
            
        }
        ?>
        
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        <br>
        <form action="/plantitoshop/checkout.php" method="post">
            <label class="form-label">Shipping:</label>
            <select class="form-select" name="shipping">
                <option selected value="1">Standard: &#x20B1;40</option>
                <option value="2">Fast Delivery: &#x20B1;120</option>
            </select>
            <button class="btn btn-warning" name="checkout">Checkout</button>
        </form>
        
    </div>
</body>
</html>
