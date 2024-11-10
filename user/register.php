<?php
include("../includes/header.php");

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
    <div class="container-fluid container-lg">
        <?php include("../includes/alert.php"); ?>
        <form action="store.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="mb-3">
                <label for="password2" class="form-label">confirm password</label>
                <input type="password" class="form-control" id="password2" name="confirmPass">
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>