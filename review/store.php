<?php
    session_start();
    include('../includes/config.php');
    if(isset($_POST['create_send'])){
        $user_id = $_POST['email'];
        $rev_num = $_POST['rev_num'];
        $prod_id = $_POST['prod'];
        $message = $_POST['rev_msg'];

        $query = "INSERT INTO review (user_id, prod_id, rev_num, rev_msg) VALUES ('$user_id', '$prod_id', '$rev_num', '$message')";
        $result = mysqli_query($conn, $query);

        if($result){
            header("Location: /plantitoshop/review/");
            exit();
        }
    }
?>