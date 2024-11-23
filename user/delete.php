<?php
    session_start();
    include('../includes/config.php');
    include('../includes/notAdminRedirect.php');
    $check_admin_sql = "SELECT COUNT(*) AS total_users, SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) AS admin_users FROM user";
    $admin_query = mysqli_query($conn, $check_admin_sql);
    $users = mysqli_fetch_assoc($admin_query);

    $d_id = $_POST['delete_id'];

    if($d_id == $_SESSION['user_id']){
        $_SESSION['message'] = "You are attempting to delete your own account while logged in as an administrator. This action is not allowed. Please contact another administrator for assistance.";
        header("Location: index.php");
        exit();
    }

    if($users['admin_users'] == 1 && $users['total_users'] == 1){
        $_SESSION['message'] = "Cannot delete user. This user is the last remaining administrator. Please assign another user as an administrator before proceeding.";
        header("Location: index.php");
        exit();
    }

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