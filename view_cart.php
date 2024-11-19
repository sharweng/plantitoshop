<?php
    session_start();
    include('includes/config.php');
    include('includes/headerBS.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">Your Cart</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="index.php">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-2">
        <?php include("includes/alert.php"); ?>
        <?php
        $shipping_cost = 40;
        if (isset($_POST['shipping'])) {
            $shipping_cost = $_POST['shipping'] == '2' ? 120 : 40;
        }
        if (isset($_SESSION["cart_products"]) && !empty($_SESSION["cart_products"])) {
            echo "<form action='cart_update.php' method='POST'>";
            echo "<table class='table table-bordered align-middle'>";
            echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Stock</th><th>Total</th><th class='text-center'>X</th></tr>";

            $grand_total = 0;

            foreach ($_SESSION["cart_products"] as $product_id => $product) {
                $product_total = $product["prod_price"] * $product["prod_qty"];
                $grand_total += $product_total;

                echo "<tr>
                        <td>{$product['prod_name']}</td>
                        <td>&#x20B1;{$product['prod_price']}</td>
                        <td><input type='number' name='product_qty[{$product_id}]' value='{$product['prod_qty']}' min='1' class='form-control' style='max-width:70px'></td>
                        <td>{$product['stk_qty']}</td>
                        <td>&#x20B1;{$product_total}</td>
                        <td class='text-center'><input type='checkbox' class='form-check-input' name='remove_code[]' value='{$product_id}'></td>
                    </tr>";
            }
           
            $grand_total += $shipping_cost;

            echo "<tr><td colspan='4'><strong>Shipping</strong></td><td colspan='2'>&#x20B1;{$shipping_cost}</td></tr>";
            echo "<tr><td colspan='4'><strong>Grand Total</strong></td><td colspan='2'>&#x20B1;{$grand_total}</td></tr>";
            echo "</table>";
            echo "<button type='submit' class='btn btn-success w-100'>Update Cart</button>";
            echo "</form>";
        } else {
            echo "<div class=\"d-flex justify-content-center\">
                <h1 class=\"fw-bold m-2\">Your cart is empty!</h1>
            </div>";
        }
        ?>
        <form action="" method="post" class="gap-1 row d-flex justify-content-center my-2">
            <div class="">
                <div class="row d-flex align-items-center">
                    <label class="form-label col-3">Shipping:</label>
                    <select class="form-select col" name="shipping"  onchange="this.form.submit()">
                        <option value="1" <?php echo (!isset($_POST['shipping']) || $_POST['shipping'] == '1') ? 'selected' : ''; ?>>Standard: &#x20B1;40</option>
                        <option value="2" <?php echo (isset($_POST['shipping']) && $_POST['shipping'] == '2') ? 'selected' : ''; ?>>Fast Delivery: &#x20B1;120</option>
                    </select>
                </div>
            </div>
        </form>
        <form action="/plantitoshop/checkout.php" method="post" class="gap-1 row d-flex justify-content-center my-2">
            <button class="btn btn-warning w-50" name="checkout">Checkout</button>
        </form>
        </div>
    </div>
</body>
</html>
<?php
    include('includes/footer.php');
?>
