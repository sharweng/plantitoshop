<?php
    session_start();
    include("../includes/config.php");

    include('../includes/headerBS.php');

    if(isset($_POST['submit'])) {
        $error = false;
        $u_id = $_SESSION['user_id'];
        $currPass = sha1(trim($_POST['currpassword']));
        $pass = trim($_POST['password']);
        $passCon = trim($_POST['confirmPass']);
        $sql = "SELECT email, password FROM user WHERE user_id = $u_id";
        $DBpass = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($DBpass)){
            $currpassDB = $row['password'];
            $emailDB = $row['email'];
        }
        $email = $emailDB;
        $password = $currpassDB;
        if($emailDB != $_POST['email']){
            $curremail = trim($_POST['email']);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $curremail)){
                $_SESSION['emailErr'] = "Error: please enter a valid email. ";
                header("Location: security.php");
                $error = true;
            }else{
                $email = $curremail;
            }
        }
        if(!empty($_POST['currpassword'])&&($currPass != $currpassDB)){
            $_SESSION['currpassErr'] = 'Error: wrong current password.';
            header("Location: security.php");
            $error = true;
        }
        if(!empty($_POST['password'])&&!empty($_POST['confirmPass'])){
            if($pass == $passCon && $currPass == $currpassDB){
                if(!preg_match("/^.{12,}$/", $pass)){
                    $_SESSION['passErr'] = "Error: password must be atleast 12 characters long. ";
                    header("Location: security.php");
                    $error = true;
                }else{
                    $password = sha1($pass);
                }
            }else{
                $_SESSION['passErr'] = 'Error: new password doesn\'t match';
                header("Location: security.php");
                $error = true;
            }
        }
        $udsql = "UPDATE user SET email = '$email', password = '$password' WHERE user_id = $u_id";
        $result = mysqli_query($conn, $udsql);
        if($result&&!$error){
            $_SESSION['success'] = 'Security settings saved successfully.';
            header("Location: security.php");
            $_SESSION['email'] = $email;    
        }
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('../includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">This is the security page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-2">
                <a href="/plantitoshop/user/profile.php">
                    <button class="btn btn-success">Profile</button>
                </a>
                <button class="btn btn-success" disabled>Security</button>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4 pt-4">
            <form action="" method="post">
                <?php include("../includes/alert.php"); ?>    
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php
                    echo $_SESSION['email'];
                ?>">
                <label class="form-text text-danger"><?php 
                    if(isset($_SESSION['emailErr'])){
                        echo $_SESSION['emailErr'];
                        unset($_SESSION['emailErr']);
                    }?></label><br>
                <label class="form-label">Current Password:</label>
                <input type="password" class="form-control" name="currpassword">
                <label class="form-text text-danger"><?php 
                    if(isset($_SESSION['currpassErr'])){
                        echo $_SESSION['currpassErr'];
                        unset($_SESSION['currpassErr']);
                    }?></label><br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirmPass">
                    </div>
                </div>
                <label class="form-text text-danger"><?php 
                    if(isset($_SESSION['passErr'])){
                        echo $_SESSION['passErr'];
                        unset($_SESSION['passErr']);
                    }?></label><br>
                <button class="btn btn-success w-100 form-btn my-2" name="submit">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>