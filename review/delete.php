<?php
    session_start();
    include('../includes/config.php');

    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];

        $query = "DELETE FROM review WHERE rev_id = '$id'";
        $result = mysqli_query($conn, $query);
        
        if($result){
            header("Location: /plantitoshop/review/");
            exit();
        }
    }
?>