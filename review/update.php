<?php
    session_start();
    include('../includes/config.php');
    if(isset($_POST['create_send'])){
        $rev_id = $_SESSION['rev_id'];
        $user_id = $_POST['email'];
        $rev_num = $_POST['rev_num'];
        $prod_id = $_POST['prod'];
        $message = $_POST['rev_msg'];

        $query = "UPDATE review SET user_id = '$user_id', prod_id = '$prod_id', rev_num = '$rev_num', rev_msg = '$message' WHERE rev_id = $rev_id";
        $result = mysqli_query($conn, $query);

        if($result){
            header("Location: /plantitoshop/review/");
            exit();
        }
    }
?>