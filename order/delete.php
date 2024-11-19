<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderinfo_id = $_POST['delete_id'];
    $sql = "DELETE FROM orderinfo WHERE orderinfo_id='$orderinfo_id'";
    mysqli_query($conn, $sql);
    header('Location: /plantitoshop/order/');
}
?>
