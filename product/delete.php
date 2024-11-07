<?php
    session_start();
    include('../includes/config.php');
    $d_id = $_POST['delete_id'];
    if(isset($_POST['delete_id'])){
        $d_id = $_POST['delete_id'];
        $sql = "DELETE FROM product WHERE prod_id = {$d_id}";
        $result = mysqli_query($conn, $sql);

        $sql = "DELETE FROM image WHERE prod_id = {$d_id}";
        $result2 = mysqli_query($conn, $sql);

        if($result && $result2){
            header("Location: index.php");
        }
    }
?>