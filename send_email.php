<?php
    session_start();
    include('includes/config.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    if(isset($_POST['send_order_receipt'])||isset($_SESSION['view_id'])){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'plantitoshop@gmail.com';
        $mail->Password = 'mjuk ubhp wsen telb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $orderinfo_id = $_SESSION['view_id'];

        // Fetch order details from the view
        $order_query = "SELECT * FROM order_transaction_details WHERE orderinfo_id = ?";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("i", $orderinfo_id);
        $stmt->execute();
        $order_result = $stmt->get_result();

        // Fetch general order info (assuming all rows have the same order info)
        $order_general = $order_result->fetch_assoc();
        $order_result->data_seek(0); // Reset result pointer for detailed items

        $mail->setFrom('plantitoshop@gmail.com', 'Plantito\'s Shop - Green Vibes, Always');
        $mail->addAddress($order_general['customer_email'], $order_general['customer_name']);

        $mail->isHTML(true);

        $mail->Subject = "Order Receipt";
        $mail->Body = "
        <html>
        <head>
            <style>
                /* Add custom styles for email table */
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            <h3 class=\"mt-3 fw-bold text-center\">Order Details</h3>
            <table style=\"width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid #ddd;\">
                <tr>
                    <th colspan=\"2\">
                        Order #{$order_general['orderinfo_id']}
                    </th>
                </tr>
                <tr>
                    <td><strong>Order Date:</strong></td>
                    <td>{$order_general['date_placed']}</td>
                </tr>
                <tr>
                    <td><strong>Customer:</strong></td>
                    <td>{$order_general['customer_name']}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{$order_general['customer_email']}</td>
                </tr>
                <tr>
                    <td><strong>Shipping Address:</strong></td>
                    <td>{$order_general['shipping_address']}</td>
                </tr>
                <tr>
                    <td><strong>Order Status:</strong></td>
                    <td>{$order_general['order_status']}</td>
                </tr>
            </table>
            <h3 class=\"mt-3 fw-bold text-center\">Ordered Products</h3>
            <table class=\"table table-bordered mt-2\">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>          
                    </tr>
                </thead>
                <tbody>";
                
                $subtotal = 0;
                while ($item = $order_result->fetch_assoc()): 
                    $subtotal += $item['total_price'];
                    $grand_total_calculated = $subtotal + $order_general['shipping_fee'];
                    $mail->Body .= "<tr>
                        <td>{$item['product_name']}</td>
                        <td>&#x20B1;".number_format($item['unit_price'], 2)."</td>
                        <td>{$item['quantity_ordered']}</td>
                        <td>&#x20B1;".number_format($item['total_price'], 2)."</td>
                    </tr>";
                endwhile;
                
                $mail->Body .= "<tr>
                    <td colspan=\"3\"><strong>Subtotal</strong></td>
                    <td>&#x20B1;".number_format($subtotal, 2)."</td>
                </tr>
                <tr>
                    <td colspan=\"3\"><strong>Shipping Fee</strong></td>
                    <td>&#x20B1;".number_format($order_general['shipping_fee'], 2)."</td>
                </tr>
                <tr>
                    <td colspan=\"3\"><strong>Grand Total</strong></td>
                    <td>&#x20B1;".number_format($grand_total_calculated, 2)."</td>
                </tr>
                </tbody>
            </table>
        </body>
        </html>
        ";
        if($mail->send()){
            if($_SESSION['fromAdmin']==true){
                $_SESSION['success'] = "Order receipt successfully sent.";
                header("Location: /plantitoshop/order/order_view.php");
            }else{
                header("Location: /plantitoshop/view_cart.php");
            }
        }else{
            if($_SESSION['fromAdmin']==true){
                $_SESSION['message'] = "Order receipt not sent.";
                header("Location: /plantitoshop/order/order_view.php");
            }else{
                header("Location: /plantitoshop/view_cart.php");
            }   
        } 
    }

?>