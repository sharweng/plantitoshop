<?php
    session_start();
    include('../includes/config.php');

    if(isset($_POST['updateOD'])){
        $orderinfo_id = $_SESSION['view_id'];
        $user_id = $_POST['email'];
        $date_placed = $_POST['date_placed'];
        $stat_id = $_POST['stat_id'];
        $shipping = $_POST['shipping'];

        $sql_orderinfo = "UPDATE orderinfo SET user_id = '$user_id', date_placed = '$date_placed', stat_id = '$stat_id', shipping = '$shipping' WHERE orderinfo_id = $orderinfo_id";
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
