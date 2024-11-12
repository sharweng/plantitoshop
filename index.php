<?php
    session_start();
    if(!isset($_SESSION['roleDesc'])){
        $_SESSION['roleDesc'] = "";
    }
        
    if($_SESSION['roleDesc'] == 'admin')
        include('../includes/adminHeader.php');
    else
        include('../includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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