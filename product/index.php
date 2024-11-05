<?php
    session_start();
    if($_SESSION['isAdmin'] == true)
        include('../includes/adminHeader.php');
    else
        include('../includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        <?php include('../includes/styles/style.css') ?>
    </style>
</head>
<body>
    <h1>This is the product page.</h1>
    <a href="create.php">
        <button class="add-button">ADD</button>
    </a>
</body>
</html>
<?php
    include('../includes/footer.php');
?>