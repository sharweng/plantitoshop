<?php
    session_start();
    include("../includes/config.php");

    $_SESSION['lname'] = trim($_POST['lname']);
    $_SESSION['fname'] = trim($_POST['fname']);
    $_SESSION['email'] = trim($_POST['email']);
    $_SESSION['pass'] = trim($_POST['password']);
    $_SESSION['cpass'] = trim($_POST['confirmPass']);
    $_SESSION['add'] = trim($_POST['address']);
    $_SESSION['phone'] = trim($_POST['phone']);

    $_SESSION['lnameErr'] = "";
    $_SESSION['fnameErr'] = "";
    $_SESSION['emailErr'] = "";
    $_SESSION['passErr'] = "";
    $_SESSION['addErr'] = "";
    $_SESSION['phoneErr'] = "";
    $_SESSION['pfpErr'] = "";

    if(isset($_POST['submit'])){
        if(empty($_POST['lname'])){
            $_SESSION['lnameErr'] = "Error: please enter a last name. ";
            header("Location: register.php");
        }else{
            $lname = trim($_POST['lname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $lname)){
                $_SESSION['lnameErr'] = "Error: please enter a valid last name. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['fname'])){
            $_SESSION['fnameErr'] = "Error: please enter a first name. ";
            header("Location: register.php");
        }else{
            $fname = trim($_POST['fname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $fname)){
                $_SESSION['fnameErr'] = "Error: please enter a valid first name. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['email'])){
            $_SESSION['emailErr'] = "Error: please enter an email. ";
            header("Location: register.php");
        }else{
            $email = trim($_POST['email']);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
                $_SESSION['emailErr'] = "Error: please enter a valid email. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['password'])||empty($_POST['confirmPass'])){
            $_SESSION['passErr'] = "Error: please enter a both passwords. ";
            header("Location: register.php");
        }elseif($_POST['password']!=$_POST['confirmPass']){
            $_SESSION['passErr'] = "Error: password does not match. ";
            header("Location: register.php");
        }else{
            $pass = trim($_POST['password']);
            if(!preg_match("/^.{12,}$/", $pass)){
                $_SESSION['passErr'] = "Error: password must be atleast 12 characters long. ";
                header("Location: register.php");
            }
        }
    
        if(empty($_POST['address'])){
            $_SESSION['addErr'] = "Error: please enter an address. ";
            header("Location: register.php");
        }else{
            $add = trim($_POST['address']);
            if(!preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add)){
                $_SESSION['addErr'] = "Error: please enter a valid address. ";
                header("Location: register.php");
            }
        }

        if(empty($_POST['phone'])){
            $_SESSION['phoneErr'] = "Error: please enter a phone number. ";
            header("Location: register.php");
        }else{
            $phone = trim($_POST['phone']);
            if(!preg_match("/^\d{11}$/", $phone)){
                $_SESSION['phoneErr'] = "Error: please enter a 11 digit long phone number. ";
                header("Location: register.php");
            }
        }

        if(isset($_FILES['pfp_path'])){
            if(!$_FILES['img_path']['type'] == "image/*"){
                $_SESSION['pfpErr'] = 'Error: please upload one file.';
                header("Location: register.php");    
            }  
        }

        if((preg_match("/^[A-Za-z' -]{2,50}$/", $lname))&&(preg_match("/^[A-Za-z' -]{2,50}$/", $fname))
        &&(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        &&(preg_match("/^.{12,}$/", $pass))&&(preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add))
        &&(preg_match("/^\d{11}$/", $phone))&&(isset($_FILES['pfp_path']))){
            $password = sha1($pass);
            $role = $_POST['role'];

            $source = $_FILES['pfp_path']['tmp_name'];
            $target = 'images/' . $_FILES['pfp_path']['name'];
            move_uploaded_file($source, $target) or die("Couldn't copy");

            $sql = "INSERT INTO user (email, password, lname, fname, addressline, phone, pfp_path, role_id)VALUES
            ('$email', '$password', '$lname', '$fname', '$add', '$phone', '$target', $role)";
            $result = mysqli_query($conn, $sql);
            if($result){
                $_SESSION['lname'] = '';
                $_SESSION['fname'] = '';
                $_SESSION['email'] = '';
                $_SESSION['pass'] = '';
                $_SESSION['cpass'] = '';
                $_SESSION['add'] = '';
                $_SESSION['phone'] = '';
                header("Location: /plantitoshop/");
            }
        }
    }
?>
