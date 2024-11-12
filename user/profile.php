<?php
    session_start();
    include("../includes/config.php");

    if($_SESSION['roleDesc'] == 'admin')
        include('../includes/adminHeader.php');
    else
        include('../includes/header.php');

    $photoPreview = ''; 

    if (isset($_POST['submit'])) {
        $lname = trim($_POST['lname']);
        $fname = trim($_POST['fname']);
        $addressline = trim($_POST['addressline']);
        $phone = trim($_POST['phone']);
        $role_id = trim($_POST['role_id']);

        // Handle the file upload
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
            $targetDir = "../uploads/profile_photos/";
            $fileName = basename($_FILES['profile_photo']['name']);
            $targetFilePath = $targetDir . $fileName;

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFilePath)) {
                // Save relative path to database
                $profilePhotoPath = "../uploads/profile_photos/" . $fileName;
            } else {
                // Handle error
                $profilePhotoPath = "images/default_profile.png"; // Placeholder if upload fails
            }
        } else {
            $profilePhotoPath = "images/default_profile.png"; // Default image if no file is uploaded
        }

        // Insert user details into database
        $sql = "INSERT INTO user (lname, fname, addressline, phone, role_id, profile_photo) 
                VALUES ('$lname', '$fname', '$addressline', '$phone', '$role_id', '$profilePhotoPath')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['success'] = 'Profile saved';
            header("Location: profile.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    <h1 class="text-center p-2 fw-bold">This is the profile page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-2">
                <button class="btn btn-success" disabled>Profile</button>
                <a href="/plantitoshop/user/security.php">
                    <button class="btn btn-success">Security</button>
                </a>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4 pt-4">
            <div class="row d-flex justify-content-center align-items-center text-center">
                <div class="col-md-6">
                    <img src="" class="rounded-circle" style="width: 150px; height: 150px; object-fit: contain;">
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                </div>
                <div class="col-md-6 ">
                    <!-- Profile picture upload button-->
                    <input class="form-control" type="file" name="profile_photo" accept="image/*">
                    <button type="submit" class="btn btn-success w-100 form-btn my-2" >UPLOAD</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="fname">
                    <label class="form-text"></label><br>
                </div>
                <div class="col-md-6">
                    <label class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="fname">
                    <label class="form-text"></label><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Address:</label>
                    <input type="text" class="form-control" name="fname">
                    <label class="form-text"></label><br>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone Number:</label>
                    <input type="text" class="form-control" name="fname">
                    <label class="form-text"></label><br>
                </div>
            </div>
            <button class="btn btn-success w-100 form-btn my-2" name="submit">SAVE CHANGES</button>
        </div>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?>
