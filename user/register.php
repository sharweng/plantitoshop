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
    <h1 class="text-center p-2 fw-bold">This is the register page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/user/login.php">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-2">
            <?php include("../includes/alert.php"); ?>
            <form action="store.php" method="POST">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
                <label class="form-text"></label><br>

                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                <label class="form-text"></label><br>
                
                <label for="password2" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="password2" name="confirmPass">
                <label class="form-text"></label><br>

                <button type="submit" class="btn btn-success w-100 form-btn my-2" >Register</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>