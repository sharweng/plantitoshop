<?php
    session_start();
    include('../includes/config.php');
    $d_id = $_POST['delete_id'];
    if(isset($_POST['delete_id'])){
        $d_id = $_POST['delete_id'];
        $sql = "DELETE FROM user WHERE user_id = {$d_id}";
        $result = mysqli_query($conn, $sql);

        if($result){
            header("Location: index.php");
        }
    }
?>