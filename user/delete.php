<?php
    session_start();
    include('../includes/config.php');
    $d_id = $_POST['delete_id'];
    if(isset($_POST['delete_id'])){
        $d_id = $_POST['delete_id'];

        $sql = "SELECT pfp_path FROM user WHERE user_id = {$d_id}";
        $result2 = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result2)){
            if($row['pfp_path'] != 'images/default-avatar-icon.jpg')
                unlink($row['pfp_path']);
        }

        $sql = "DELETE FROM user WHERE user_id = {$d_id}";
        $result = mysqli_query($conn, $sql);

        if($result){
            header("Location: index.php");
        }
    }
?>