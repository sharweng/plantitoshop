<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPass = trim($_POST['confirmPass']);
if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPass'])) {
    $_SESSION['message'] = "\nAll fields are required. Please fill out each field.";
    header("Location: register.php");
    exit();
}
if ($password !== $confirmPass) {
    $_SESSION['message'] = "\nPasswords do not match.";
    header("Location: register.php");
    exit();
}
$password = sha1($password);
$sql = "INSERT INTO user (email,password) VALUES('$email', '$password')";

$result = mysqli_query($conn, $sql);
if ($result ) {
    $_SESSION['user_id'] = mysqli_insert_id($conn);
    header("Location: profile.php");
}
