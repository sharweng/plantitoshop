<?php
    session_start();
    include("../includes/config.php");

    include('../includes/headerBS.php');

    $sql = "SELECT * FROM role";
    $roles = mysqli_query($conn, $sql);

    $addUser = false;
    if(isset($_POST['add_user'])){
        $_SESSION['lname'] = '';
        $_SESSION['fname'] = '';
        $_SESSION['email'] = '';
        $_SESSION['pass'] = '';
        $_SESSION['cpass'] = '';
        $_SESSION['add'] = '';
        $_SESSION['phone'] = '';
        $addUser =  true;
        $_SESSION['adminEdit'] = $addUser;
    }

    if(!isset($_SESSION['adminEdit'])){
        $_SESSION['adminEdit'] = false;
    }

    if(isset($_POST['back'])){
        $_SESSION['lname'] = '';
        $_SESSION['fname'] = '';
        $_SESSION['email'] = '';
        $_SESSION['pass'] = '';
        $_SESSION['cpass'] = '';
        $_SESSION['add'] = '';
        $_SESSION['phone'] = '';
        if($_SESSION['adminEdit']){
            header("Location: /plantitoshop/user/");
        }else{
            header("Location: login.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
    <h1 class="text-center p-2 fw-bold">This is the register page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <form action="" method="post">
                    <button class="btn btn-success" name="back">BACK</button>
                </form>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
        <?php include("../includes/alert.php"); ?>
            <form action="store.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lname" value="<?php
                        if(isset($_SESSION['lname'])){
                            echo $_SESSION['lname'];
                        }?>">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="fname" value="<?php
                        if(isset($_SESSION['fname'])){
                            echo $_SESSION['fname'];
                        }?>">
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
                    }
                ?></label><br>
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php
                    if(isset($_SESSION['email'])){
                        echo $_SESSION['email'];
                    }?>">
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['emailErr'])){
                        echo $_SESSION['emailErr'];
                        unset($_SESSION['emailErr']);
                    }
                ?></label><br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" value="<?php
                        if(isset($_SESSION['pass'])){
                            echo $_SESSION['pass'];
                        }?>">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirmPass" value="<?php
                        if(isset($_SESSION['cpass'])){
                            echo $_SESSION['cpass'];
                        }?>">
                    </div>
                </div>
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['passErr'])){
                        echo $_SESSION['passErr'];
                        unset($_SESSION['passErr']);
                    }
                ?></label><br>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" value="<?php
                        if(isset($_SESSION['add'])){
                            echo $_SESSION['add'];
                        }?>">
                        <label class="form-text"></label><br>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number:</label>
                        <input type="text" class="form-control" name="phone" value="<?php
                        if(isset($_SESSION['phone'])){
                            echo $_SESSION['phone'];
                        }?>">
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
                    }
                ?></label><br>
                <?php
                    $counter = 1;
                    if($_SESSION['adminEdit']){
                        echo "<label class=\"form-label\">Role:</label>
                        <select class=\"form-select\" name=\"role\">";
                    }else{
                        echo "<label hidden class=\"form-label\">Role:</label>
                        <select hidden class=\"form-select\" name=\"role\">";
                    }
                    while($role = mysqli_fetch_array($roles)){
                        if($counter == 2){
                            echo "<option selected value=\"{$role['role_id']}\">{$role['description']}</option>";
                        }else{
                            echo "<option value=\"{$role['role_id']}\">{$role['description']}</option>";
                        }
                        $counter++;
                    }
                ?>
                </select>
                <label class="form-text text-danger"></label><br>

                <label class="form-label">Profile Picture:</label>
                <input class="form-control" type="file" name="pfp_path" accept="image/*">
                <label class="form-text text-danger"><?php
                    if(isset($_SESSION['pfpErr'])){
                        echo $_SESSION['pfpErr'];
                        unset($_SESSION['pfpErr']);
                    }
                ?></label><br>

                <button type="submit" class="btn btn-success w-100 form-btn my-2" name="submit">REGISTER</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>