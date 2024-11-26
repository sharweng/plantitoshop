<?php
    session_start();
    include("../includes/config.php");
    include('../includes/notUserRedirect.php');
    include('../includes/headerBS.php');

    if(isset($_POST['back'])){
        $_SESSION['lname'] = '';
        $_SESSION['fname'] = '';
        $_SESSION['add'] = '';
        $_SESSION['phone'] = '';
    }
    $u_id = $_SESSION['user_id'];
    $getInfo = "SELECT lname, fname, addressline, phone FROM user WHERE user_id = $u_id";
    $info = mysqli_query($conn, $getInfo);
    while($row = mysqli_fetch_array($info)){
        $_SESSION['lname'] = $row['lname'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['add'] = $row['addressline'];
        $_SESSION['phone'] = $row['phone'];
    }
    

    $u_id = $_SESSION['user_id'];
    if(isset($_POST['upload'])){
        if(isset($_FILES['profile_photo'])&&!empty($_FILES['profile_photo'])){
            $source = $_FILES['profile_photo']['tmp_name'];
            $target = 'images/' . $_FILES['profile_photo']['name'];
            if(move_uploaded_file($source, $target)){
                $sql = "SELECT pfp_path FROM user WHERE user_id = {$u_id}";
                $result2 = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result2);
                $checksql = "SELECT COUNT(pfp_path) as count FROM user WHERE pfp_path = '{$row['pfp_path']}'";
                $checkresult = mysqli_query($conn, $checksql);
                $check = mysqli_fetch_assoc($checkresult);
                if($row['pfp_path'] != $target){
                    if($row['pfp_path'] != 'images/default-avatar-icon.jpg' && $check['count'] == 1)
                        unlink($row['pfp_path']);
                    $sql = "UPDATE user SET pfp_path = '$target' WHERE user_id = $u_id";
                    $upload = mysqli_query($conn, $sql);
                }else{
                    $upload = true;
                }
            }else{
                $upload = false;
            }
        }
        if($upload){
            $_SESSION['success'] = 'Profile picture updated successfully.';
            header("Location: profile.php");
            exit();
        }
            
    }

    if (isset($_POST['submit'])) {
        $lname = trim($_POST['lname']);
        $fname = trim($_POST['fname']);
        $addressline = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        
        if(empty($_POST['lname'])){
            $_SESSION['lnameErr'] = "Error: please enter a last name. ";
            header("Location: profile.php");
        }else{
            $lname = trim($_POST['lname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $lname)){
                $_SESSION['lnameErr'] = "Error: please enter a valid last name. ";
                header("Location: profile.php");
            }
        }
    
        if(empty($_POST['fname'])){
            $_SESSION['fnameErr'] = "Error: please enter a first name. ";
            header("Location: profile.php");
        }else{
            $fname = trim($_POST['fname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $fname)){
                $_SESSION['fnameErr'] = "Error: please enter a valid first name. ";
                header("Location: profile.php");
            }
        }
        if(empty($_POST['address'])){
            $_SESSION['addErr'] = "Error: please enter an address. ";
            header("Location: profile.php");
        }else{
            $add = trim($_POST['address']);
            if(!preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add)){
                $_SESSION['addErr'] = "Error: please enter a valid address. ";
                header("Location: profile.php");
            }
        }

        if(empty($_POST['phone'])){
            $_SESSION['phoneErr'] = "Error: please enter a phone number. ";
            header("Location: profile.php");
        }else{
            $phone = trim($_POST['phone']);
            if(!preg_match("/^\d{11}$/", $phone)){
                $_SESSION['phoneErr'] = "Error: please enter a 11 digit long phone number. ";
                header("Location: profile.php");
            }
        }

        if((preg_match("/^[A-Za-z' -]{2,50}$/", $lname))&&(preg_match("/^[A-Za-z' -]{2,50}$/", $fname))
        &&(preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add))
        &&(preg_match("/^\d{11}$/", $phone))){
            $sql = "UPDATE user SET lname = '$lname', fname = '$fname', addressline = '$addressline', phone = '$phone' WHERE user_id = $u_id";
            $result = mysqli_query($conn, $sql);
            if($result){
                $_SESSION['success'] = 'Profile saved successfully.';
                header("Location: profile.php");
            }
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
    <h1 class="text-center p-2 fw-bold">Profile</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <form action="/plantitoshop/" method="post">
                    <button class="btn btn-success" name="back">BACK</button>
                </form>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-2">
                <button class="btn btn-success" disabled>Profile</button>
                <a href="/plantitoshop/user/security.php">
                    <button class="btn btn-success">Security</button>
                </a>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4 pt-4">
            <?php include("../includes/alert.php"); ?>   
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row d-flex justify-content-center align-items-center text-center">
                    <div class="col-md-6">
                        <img src="<?php 
                            $sql = "SELECT pfp_path FROM user WHERE user_id = {$_SESSION['user_id']}";
                            $DBpath = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($DBpath)){
                                echo $row['pfp_path'];
                            }
                            ?>" 
                        class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label">Change Profile Picture:</label>
                        <input class="form-control" type="file" name="profile_photo" accept="image/*">
                        <button type="submit" class="btn btn-success w-100 form-btn my-2" name="upload" >UPLOAD</button>
                    </div>
                </div>
            </form>
            <form action="" method="post" enctype="multipart/form-data">
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
                <button class="btn btn-success w-100 form-btn my-2" name="submit">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>
