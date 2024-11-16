<?php
    session_start();
    include("../includes/config.php");

    include('../includes/headerBS.php');

    if(isset($_POST['update_id'])){
        $ud_id = $_POST['update_id'];
        $_SESSION['update_id'] = $ud_id;
    }else
        $ud_id = $_SESSION['update_id'];
        
    if(isset($_POST['back'])){
        $_SESSION['lname'] = '';
        $_SESSION['fname'] = '';
        $_SESSION['add'] = '';
        $_SESSION['phone'] = '';
        $_SESSION['email'] = '';
        $_SESSION['pass'] = '';
        $_SESSION['currpass'] = '';
    }

    $getInfo = "SELECT * FROM user WHERE user_id = $ud_id";
    $info = mysqli_query($conn, $getInfo);
    while($row = mysqli_fetch_array($info)){
        $_SESSION['lname'] = $row['lname'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['add'] = $row['addressline'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['pass'] = $row['password'];
        $_SESSION['role_id'] = $row['role_id'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
    <h1 class="text-center p-2 fw-bold">Edit User</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <form action="/plantitoshop/user/" method="post">
                    <button class="btn btn-success" name="back">BACK</button>
                </form>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-2">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4 pt-4">
            <?php include("../includes/alert.php"); ?>   
            <form action="update.php" method="post" enctype="multipart/form-data">
                <div class="row d-flex justify-content-center align-items-center text-center">
                    <div class="col-md-6">
                        <img src="<?php 
                            $sql = "SELECT pfp_path FROM user WHERE user_id = $ud_id";
                            $DBpath = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($DBpath)){
                                echo $row['pfp_path'];
                            }
                            ?>" 
                        class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    </div>
                    <div class="col-md-6 ">
                        <input class="form-control" type="file" name="profile_photo" accept="image/*">
                        <button type="submit" class="btn btn-success w-100 form-btn my-2" name="upload" >UPLOAD</button>
                    </div>
                </div>
            </form>
            <form action="update.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lname" value="<?php
                            echo $_SESSION['lname'];
                        ?>">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="fname" value="<?php
                            echo $_SESSION['fname'];
                        ?>">
                    </div>
                </div>
                <label class="form-text text-danger"><?php 
                    if(isset($_SESSION['lnameErr'])){
                        echo $_SESSION['lnameErr'];
                        unset($_SESSION['lnameErr']);
                    }
                    if(isset($_SESSION['fnameErr'])){
                        echo $_SESSION['fnameErr'];
                        unset($_SESSION['fnameErr']);
                    }?></label><br>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" value="<?php
                            echo $_SESSION['add'];
                        ?>">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number:</label>
                        <input type="text" class="form-control" name="phone" value="<?php
                            echo $_SESSION['phone'];
                        ?>">  
                    </div>
                </div>
                <label class="form-text text-danger"><?php 
                    if(isset($_SESSION['addErr'])){
                        echo $_SESSION['addErr'];
                        unset($_SESSION['addErr']);
                    }
                    if(isset($_SESSION['phoneErr'])){
                        echo $_SESSION['phoneErr'];
                        unset($_SESSION['phoneErr']);
                    }?></label><br>
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php
                    echo $_SESSION['email'];
                ?>">
                <label class="form-text"></label><br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" ">
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
                    }
                    ?></label><br>
                <label class="form-label">Role:</label>
                <select class="form-select" name="role">
                <?php
                    $counter = 1;
                    $sql = "SELECT * FROM role";
                    $roles = mysqli_query($conn, $sql);
                    while($role = mysqli_fetch_array($roles)){
                        if($role['role_id']  == $_SESSION['role_id']){
                            echo "<option selected value=\"{$role['role_id']}\">{$role['description']}</option>";
                        }else{
                            echo "<option value=\"{$role['role_id']}\">{$role['description']}</option>";
                        }
                    }
                ?>
                </select>
                <label class="form-text text-danger"></label><br>
                <button class="btn btn-success w-100 form-btn my-2" name="submit">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>
