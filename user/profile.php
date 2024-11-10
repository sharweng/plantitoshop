<?php
include("../includes/header.php");
include("../includes/config.php");

$photoPreview = ''; // Initialize photo preview variable

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
    <div class="container-xl px-4 mt-4">
        <?php include("../includes/alert.php"); ?>
        <!-- Account page navigation-->
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0" href="#">Profile</a>
        </nav>
        <hr class="mt-0 mb-4">
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" 
                            src="<?php echo htmlspecialchars($photoPreview); ?>" 
                            alt="Profile Picture" 
                            style="width: 150px; height: 150px; object-fit: contain;">
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        <!-- Profile picture upload button-->
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewImage(event)" required>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputFirstName">First name</label>
                                    <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" name="fname" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputLastName">Last name</label>
                                    <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" name="lname" required>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="address">Address</label>
                                    <input class="form-control" id="address" type="text" placeholder="Enter your address" name="addressline" required>
                                </div>
                            
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" id="inputPhone" type="tel" placeholder="Enter your phone number" name="phone" required>
                                </div>
                                <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="role">Role</label>
                                    <input class="form-control" id="role" type="text" placeholder="choose your role" name="role_id" required>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name="submit">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
function previewImage(event) {
    const image = document.querySelector('.img-account-profile');
    image.src = URL.createObjectURL(event.target.files[0]);
}
</script>
<?php
include("../includes/footer.php");
?>
