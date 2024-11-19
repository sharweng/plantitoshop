<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createOD'])) {
    $user_id = $_POST['email'];
    $date_placed = $_POST['date_placed'];
    $stat_id = $_POST['stat_id'];
    $shipping = $_POST['shipping'];
    $prod_ids = $_POST['prod_id'];

    $sql_orderinfo = "INSERT INTO orderinfo (user_id, date_placed, stat_id, shipping) VALUES ('$user_id', '$date_placed', '$stat_id', '$shipping')";
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
?>
