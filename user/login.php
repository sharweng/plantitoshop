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
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6 col-lg-4">
            <?php include("../includes/alert.php"); ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Email address</label>
                    <input type="email" id="form2Example1" class="form-control" name="email" required />
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example2">Password</label>
                    <input type="password" id="form2Example2" class="form-control" name="password" required />
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4" name="submit">Sign in</button>

                <!-- Register link -->
                <div class="text-center">
                    <p>Not a member? <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>
