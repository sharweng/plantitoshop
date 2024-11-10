<?php

include("../includes/header.php");
include("../includes/config.php");

// After successful login, fetch user data
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pass = sha1(trim($_POST['password']));
    $sql = "SELECT user_id, email, fname, lname, profile_photo FROM user WHERE email=? AND password=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $pass);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $email, $fname, $lname, $profile_photo);
    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        // Store user info in session
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['profile_photo'] = $profile_photo; // Store profile photo path in session

        header("Location: ../index.php"); // Redirect to home page after successful login
    } else {
        $_SESSION['message'] = 'Wrong email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <h1 class="text-center p-2 fw-bold">This is the login page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-2">
            <?php include("../includes/alert.php"); ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <!-- Email input -->
                <label class="form-label">Email Address:</label>
                <input type="email" class="form-control" name="email"/>
                <label class="form-text"></label><br>
                <!-- Password input -->
                <label class="form-label">Password:</label>
                <input type="password" class="form-control" name="password"/>
                <label class="form-text"></label><br>
                <!-- Submit button -->
                <button type="submit" class="btn btn-success w-100 form-btn my-2" name="submit">Sign in</button>
                <!-- Register link -->
                <div class="text-center">
                    <p>Not a member? <a href="register.php" class="green-hover">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>
