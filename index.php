<?php
    session_start();
    $_SESSION['isAdmin'];
    if($_SESSION['isAdmin'] == true)
        include('includes/adminHeader.php');
    else
        include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        <?php include('includes/styles/style.css') ?>
    </style>
</head>
<body>
    <h1>This is the home page.</h1>
</body>
</html>
<?php
    include('includes/footer.php');
?>