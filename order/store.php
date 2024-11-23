<?php
session_start();
include('../includes/config.php');
include('../includes/notAdminRedirect.php');

if(isset($_POST['createOD'])){
    $user_id = $_POST['email'];
    $date_placed = $_POST['date_placed'];
    $stat_id = $_POST['stat_id'];
    $shipping = $_POST['shipping'];

    $sql_orderinfo = "INSERT INTO orderinfo (user_id, date_placed, date_shipped, stat_id, shipping) VALUES ('$user_id', '$date_placed', NULL, '$stat_id', '$shipping')";
    $result = mysqli_query($conn, $sql_orderinfo);

    if($result){
        header('Location: /plantitoshop/order/');
        exit();
    }else{
        $_SESSION['message'] = "Failed to create orderinfo.";
        header('Location: /plantitoshop/order/');
        exit();
    }
}
if (isset($_POST['createOI'])) {
    $oi_id = $_SESSION['view_id'];
    $prod_id = $_POST['prod'];
    $quantity = $_POST['quantity'];

    $sql_orderline = "INSERT INTO orderline (orderinfo_id, prod_id, quantity) VALUES ($oi_id, $prod_id, $quantity)";
    $result = mysqli_query($conn, $sql_orderline);

    if($result){
        header('Location: /plantitoshop/order/order_view.php');
        exit();
    }else{
        $_SESSION['message'] = "Failed to create orderinfo.";
        header('Location: /plantitoshop/order/order_view.php');
        exit();
    }
}
?>
