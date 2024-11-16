<?php
    session_start();
    include('../includes/config.php');
    include('../includes/notAdminRedirect.php');

    $d_id = $_POST['delete_id'];
    if(isset($_POST['delete_id'])){
        $d_id = $_POST['delete_id'];

        $sql = "SELECT img_path FROM image WHERE prod_id = {$d_id}";
        $result2 = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result2)){
            unlink($row['img_path']);
        }

        $sql = "DELETE FROM product WHERE prod_id = {$d_id}";
        $result = mysqli_query($conn, $sql);

        if($result){
            header("Location: index.php");
        }
    }
?>