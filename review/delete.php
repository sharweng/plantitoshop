<?php
    session_start();
    include('../includes/config.php');

    if (isset($_POST['delete']) || isset($_POST['delete_rev_prod'])) {
        if(isset($_POST['delete_rev_prod']))
            $id = $_POST['delete_rev_prod'];
        else
            $id = $_POST['delete'];
        $query = "DELETE FROM review WHERE rev_id = '$id'";
        $result = mysqli_query($conn, $query);
        
        if($result){
            if(isset($_POST['delete_rev_prod']))
                header("Location: /plantitoshop/view_product.php");
            else
                header("Location: /plantitoshop/review/");
            exit();
        }
    }
    
?>