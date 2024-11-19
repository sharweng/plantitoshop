<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    if(isset($_GET['email-search']))
        $keyword = strtolower(trim($_GET['email-search']));
    else
        $keyword = "";

    $email_sql = "SELECT user_id, email FROM user";
    if($keyword){
        $email_sql = $email_sql . " WHERE email LIKE '%{$keyword}%'";  
    }
    $email_query = mysqli_query($conn, $email_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
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
    <h1 class='text-center p-2 fw-bold'>Create Order</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-2 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/order/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-10 d-flex align-items-center justify-content-end gap-1">
                <div class="col-8 d-flex align-items-center justify-content-end gap-2">
                    <button class="btn btn-success" disabled>Order Details</button>
                    <a href="/plantitoshop/order/createOI.php">
                        <button class="btn btn-success">Order Items</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
                <h3 class="text-center fw-bold">Order Details</h3>
                <div class="mb-3">
                    <form action="" method="get" class="row">
                        <label for="user_id" class="form-label">Search Email:</label>
                        <div class="input-group ">
                            <input type="text" class="form-control" name="email-search">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </form>
                    <form action="store.php" method="post">
                    <label for="user_id" class="form-label">Email:</label>
                    <select class="form-select" name="email">
                        <?php
                        while($emails = mysqli_fetch_array($email_query)){
                            echo "<option value=\"{$emails['user_id']}\">{$emails['email']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date_placed" class="form-label">Date Placed:</label>
                    <input type="date" class="form-control" name="date_placed">
                </div>
                <div class="mb-3">
                    <label class="form-label col-3">Status:</label>
                    <select class="form-select col" name="stat_id">
                        <option selected value="1">Ongoing</option>
                        <option value="2">Delivered</option>
                        <option value="3">Cancelled</option>";
                    </select>
                </div>
                <div class="mb-3">
                <label class="form-label col-3">Shipping:</label>
                    <select class="form-select col" name="shipping">
                        <option selected value="40">Standard: &#x20B1;40</option>
                        <option value="120">Fast Delivery: &#x20B1;120</option>
                    </select>
                </div>
            
                <div class="row gap-1">
                    <button type="submit" class="col btn btn-success mt-3" name="createOD">CREATE ORDER INFO</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>
