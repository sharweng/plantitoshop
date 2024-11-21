<?php
    session_start();
    include("../includes/config.php");
    include('../includes/notAdminRedirect.php');

    $_SESSION['lnameErr'] = "";
    $_SESSION['fnameErr'] = "";
    $_SESSION['emailErr'] = "";
    $_SESSION['passErr'] = "";
    $_SESSION['addErr'] = "";
    $_SESSION['phoneErr'] = "";
    $_SESSION['pfpErr'] = "";

    $ud_id = $_SESSION['update_id'];
    if(isset($_POST['upload'])){
        if(isset($_FILES['profile_photo'])&&!empty($_FILES['profile_photo'])){
            $source = $_FILES['profile_photo']['tmp_name'];
            $target = 'images/' . $_FILES['profile_photo']['name'];
            
            if(move_uploaded_file($source, $target)){
                $sql = "SELECT pfp_path FROM user WHERE user_id = {$ud_id}";
                $result2 = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result2)){
                    if($row['pfp_path'] != 'images/default-avatar-icon.jpg')
                        unlink($row['pfp_path']);
                }
                $sql = "UPDATE user SET pfp_path = '$target' WHERE user_id = $ud_id";
                $upload = mysqli_query($conn, $sql);
            }else{
                $upload = false;
            }
        }
        header("Location: /plantitoshop/user/edit.php");    
    }

    if(isset($_POST['submit'])){
        $_SESSION['lname'] = trim($_POST['lname']);
        $_SESSION['fname'] = trim($_POST['fname']);
        $_SESSION['email'] = trim($_POST['email']);
        $_SESSION['pass'] = trim($_POST['password']);
        $_SESSION['currpass'] = trim($_POST['confirmPass']);
        $_SESSION['add'] = trim($_POST['address']);
        $_SESSION['phone'] = trim($_POST['phone']);
        $error = false;
        if(empty($_POST['lname'])){
            $_SESSION['lnameErr'] = "Error: please enter a last name. ";
            header("Location: edit.php");
        }else{
            $lname = trim($_POST['lname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $lname)){
                $_SESSION['lnameErr'] = "Error: please enter a valid last name. ";
                header("Location: edit.php");
            }
        }
    
        if(empty($_POST['fname'])){
            $_SESSION['fnameErr'] = "Error: please enter a first name. ";
            header("Location: edit.php");
        }else{
            $fname = trim($_POST['fname']);
            if(!preg_match("/^[A-Za-z' -]{2,50}$/", $fname)){
                $_SESSION['fnameErr'] = "Error: please enter a valid first name. ";
                header("Location: edit.php");
            }
        }
    
        if(empty($_POST['email'])){
            $_SESSION['emailErr'] = "Error: please enter an email. ";
            header("Location: edit.php");
        }else{
            $email = trim($_POST['email']);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
                $_SESSION['emailErr'] = "Error: please enter a valid email. ";
                header("Location: edit.php");
            }
        }
        
        
        if(!empty($_POST['password'])&&!empty($_POST['confirmPass'])){
            if($_POST['password']!=$_POST['confirmPass']){
                $_SESSION['passErr'] = "Error: password does not match. ";
                header("Location: edit.php");
                $error = true;
            }elseif(($_POST['password']==$_POST['confirmPass'])&&!empty($_POST['password'])&&!empty($_POST['confirmPass'])){
                $pass = trim($_POST['password']);
                if(!preg_match("/^.{12,}$/", $pass)){
                    $_SESSION['passErr'] = "Error: password must be atleast 12 characters long. ";
                    header("Location: edit.php");
                    $error = true;
                }
            }
        }

        if(empty($_POST['address'])){
            $_SESSION['addErr'] = "Error: please enter an address. ";
            header("Location: edit.php");
        }else{
            $add = trim($_POST['address']);
            if(!preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add)){
                $_SESSION['addErr'] = "Error: please enter a valid address. ";
                header("Location: edit.php");
            }
        }

        if(empty($_POST['phone'])){
            $_SESSION['phoneErr'] = "Error: please enter a phone number. ";
            header("Location: edit.php");
        }else{
            $phone = trim($_POST['phone']);
            if(!preg_match("/^\d{11}$/", $phone)){
                $_SESSION['phoneErr'] = "Error: please enter a 11 digit long phone number. ";
                header("Location: edit.php");
            }
        }

        if((preg_match("/^[A-Za-z' -]{2,50}$/", $lname))&&(preg_match("/^[A-Za-z' -]{2,50}$/", $fname))
        &&(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        &&(preg_match("/^[A-Za-z0-9\s.,'-]{5,100}$/", $add))
        &&(preg_match("/^\d{11}$/", $phone))){
            
            $role = $_POST['role'];

            if(empty($_POST['password'])&&empty($_POST['confirmPass'])){
                $sql = "UPDATE user SET email = '$email', lname = '$lname', fname = '$fname', addressline = '$add', phone = '$phone', role_id = $role WHERE user_id = $ud_id";
                $result1 = mysqli_query($conn, $sql);
            }else{
                $password = sha1($pass);
                $sql = "UPDATE user SET email = '$email', password = '$password', lname = '$lname', fname = '$fname', addressline = '$add', phone = '$phone', role_id = $role WHERE user_id = $ud_id";
                $result2 = mysqli_query($conn, $sql);
            }
                
            if($result1||$result2){
                $_SESSION['lname'] = '';
                $_SESSION['fname'] = '';
                $_SESSION['email'] = '';
                $_SESSION['pass'] = '';
                $_SESSION['currpass'] = '';
                $_SESSION['add'] = '';
                $_SESSION['phone'] = '';

                if($error)
                    header("Location: /plantitoshop/user/edit.php");
                else
                    header("Location: /plantitoshop/user/");
            }
        }
    }
?>