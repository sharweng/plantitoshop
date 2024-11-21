<?php
session_start();
include('../includes/config.php');
include('../includes/notAdminRedirect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderinfo_id'])) {
    $orderinfo_id = $_POST['orderinfo_id'];
    $sql = "DELETE FROM orderinfo WHERE orderinfo_id='$orderinfo_id'";
    mysqli_query($conn, $sql);
    header('Location: /plantitoshop/order/');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderline'])) {
    $orderinfo_id = $_POST['oi_id'];
    $prod_id = $_POST['orderline'];
    $sql = "DELETE FROM orderline WHERE orderinfo_id='$orderinfo_id' AND prod_id = '$prod_id'";
    mysqli_query($conn, $sql);
    header('Location: /plantitoshop/order/order_view.php');
}
?>
