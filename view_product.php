<?php
    session_start();
    include('includes/config.php');
    include('includes/headerBS.php');

    $prod_id = $_SESSION['prod_id'];

    if(!isset($_SESSION['img_pos']))
        $_SESSION['img_pos'] = 1;

    if(isset($_POST['next']))
        $_SESSION['img_pos']++;
    elseif(isset($_POST['previous']))
        $_SESSION['img_pos']--;

    $sql = "SELECT p.prod_id, p.description, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id WHERE p.prod_id = $prod_id";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">Product Details</h1>
    <div class="container outer-box detail-width2 p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/">
                    <button class="btn btn-success">BACK</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
            <div class="row d-flex justify-content-center">
                    <?php
                    while($row = mysqli_fetch_array($result)){
                        echo "<div class=\"col-md-6 inner-detail-width d-flex justify-content-center align-items-center\">
                                <form action=\"\" method=\"post\">
                                    <button class=\"btn btn-sm btn-success\" style=\"height: 32px;\" name=\"previous\"><</button>";
                        $sql = "SELECT img_path FROM image WHERE prod_id = {$row['prod_id']}";
                        $result2 = mysqli_query($conn, $sql);

                        $sql = "SELECT count(img_path) as count FROM image WHERE prod_id = {$row['prod_id']}";
                        $countResult = mysqli_query($conn, $sql);
                        while($row3 = mysqli_fetch_array($countResult))
                            $count = $row3['count'];

                            $counter = 1;
                            $img_pos = $_SESSION['img_pos'];
                            if($img_pos == 0){
                                $_SESSION['img_pos'] = 5;
                                $img_pos = $_SESSION['img_pos'];
                            }
                            if( $img_pos > $count){
                                $_SESSION['img_pos'] = 1;
                                $img_pos = $_SESSION['img_pos'];
                            }
                            while($row2 = mysqli_fetch_array($result2)){
                                if($img_pos == $counter){
                                    echo "<img src=\"/plantitoshop/product/{$row2['img_path']}\" class=\"object-fit-contain img-container mx-3 p-3\">";
                                }
                                $counter++;
                            }
                        echo "<button class=\"btn btn-sm btn-success\" style=\"height: 32px;\" name=\"next\">></button>
                                </form>
                            </div>
                            <div class=\"col-md-6 inner-detail-width py-2 d-flex flex-column justify-content-center align-items-center\">
                                <form action=\"cart_update.php\" method=\"post\" class=\"detail-width d-flex flex-column justify-content-center align-items-start\">
                                    <h1 class=\"fw-bold\">{$row['description']}</h1>
                                    <label class=\"form-label h4\">Price: <p class=\"h1 fw-bold d-inline\">&#x20B1;{$row['price']}</p></label>
                                    <label class=\"form-label\">Stock: {$row['quantity']}</label>
                                    <label class=\"form-label lbl-width\">Quantity:</label>
                                    <input class=\"form-control d-inline\" type=\"number\" value=\"1\" placeholder=\"1\" name=\"quantity\" min=\"1\">
                                    <input type=\"hidden\" name=\"prod_id\" value=\"{$row['prod_id']}\"/>
                                    <input class=\"form-control d-inline\" type=\"hidden\" value=\"{$row['quantity']}\" placeholder=\"1\" name=\"stock\" min=\"1\">
                                    <input type=\"hidden\" name=\"type\" value=\"add\" />
                                    <button class=\"btn btn-success w-100 form-btn my-2\" name=\"add_to_cart\">ADD TO CART</button>
                                </form>
                            </div>";
                    }
                    ?>
            </div>
            
        </div>
    </div>
    <div class="container outer-box detail-width2 p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="container inner-box border border-success border-2 py-3 px-4">
            <h3 class="fw-bold text-center">Reviews</h3>
            <?php
                if(!empty($_SESSION['user_id'])){
                    $deliveredsql = "SELECT oi.stat_id, u.user_id FROM user u INNER JOIN orderinfo oi ON u.user_id = oi.user_id WHERE oi.stat_id = 2 AND u.user_id = {$_SESSION['user_id']}";
                    $deliveredquery = mysqli_query($conn, $deliveredsql);
                    
                    if($deliveredquery->num_rows != 0){
                        echo "<button class=\"btn btn-success btn-sm\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                                ADD REVIEW
                            </button>";
                    }
                
            ?>
            <div class="mt-2 collapse" id="collapseExample">
                <div class="card card-body">
                    <form action="/plantitoshop/review/store.php" method="post">
                        <label for="message-text" class="col-form-label">Rating:</label>
                        <select name="rev_num" class="form-select">
                            <?php
                                for($i = 1; $i <= 5; $i++){
                                    echo "<option value=\"{$i}\">{$i}</option>";
                                }
                            ?>
                        </select>
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text" name="rev_msg"></textarea>
                        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="email">
                        <input type="hidden" value="<?php echo $_SESSION['prod_id'] ?>" name="prod">
                        <button class="btn btn-sm btn-success mt-3" type="submit" name="create_send_prod">Send</button>
                    </form>
                </div>
            </div>
            <?php 
                $rev_sql = "SELECT r.rev_id, r.user_id, CONCAT(u.lname,', ', u.fname) AS uname, r.rev_num, r.rev_msg FROM review r INNER JOIN user u ON u.user_id = r.user_id
                    WHERE prod_id = {$_SESSION['prod_id']}";
                $rev_query = mysqli_query($conn, $rev_sql);
                while($reviews = mysqli_fetch_array($rev_query)) {
                    echo "<div class=\"mt-2 card\">
                            <div class=\"card-header d-flex justify-content-between\">
                                <div>{$reviews['uname']}</div>";
                                if($_SESSION['user_id'] == $reviews['user_id']){
                                    echo "<div class=\"dropdown\">
                                            <button class=\"btn btn-sm\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                                <i class=\"bi bi-three-dots\"></i>
                                            </button>
                                            <ul class=\"dropdown-menu\">
                                                <li><button class=\"dropdown-item\" data-bs-toggle=\"collapse\" data-bs-target=\"#editReview{$reviews['rev_id']}\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Edit</button></li>
                                                <form action='/plantitoshop/review/delete.php' method=\"post\">    
                                                    <li><button class=\"dropdown-item\"  name=\"delete_rev_prod\" value=\"{$reviews['rev_id']}\">Delete</button></li>
                                                </form>
                                            </ul>
                                        </div>";
                                }
                            echo" </div>

                            <div class=\"mt-2 collapse card-body\" id=\"editReview{$reviews['rev_id']}\">
                                <div class=\"card card-body\">
                                    <form action=\"/plantitoshop/review/update.php\" method=\"post\">
                                        <label for=\"message-text\" class=\"col-form-label\">Rating:</label>
                                        <select name=\"rev_num\" class=\"form-select\">";
                                            for($i = 1; $i <= 5; $i++){
                                                if($i == $reviews['rev_num'])
                                                    echo "<option selected value=\"{$i}\">{$i}</option>";
                                                else
                                                    echo "<option value=\"{$i}\">{$i}</option>";
                                            }
                                        echo "</select>
                                        <label for=\"message-text\" class=\"col-form-label\">Message:</label>
                                        <textarea class=\"form-control\" id=\"message-text\" name=\"rev_msg\">{$reviews['rev_msg']}</textarea>
                                        <input type=\"hidden\" value=\"{$reviews['rev_id']}\" name=\"rev_id\">
                                        <input type=\"hidden\" value=\"{$_SESSION['user_id']}\" name=\"user\">
                                        <input type=\"hidden\" value=\"{$_SESSION['prod_id']}\" name=\"prod\">
                                        <button class=\"btn btn-sm btn-success mt-3 w-100\" type=\"submit\" name=\"update_prod\">Update</button>
                                        </form>
                                        <button class=\"btn btn-sm btn-secondary mt-1\" data-bs-toggle=\"collapse\" data-bs-target=\"#editReview\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Close</button>
                                </div>
                            </div>

                            <div class=\"card-body\">
                                <blockquote class=\"blockquote mb-0\">
                                    <p class=\"fs-6\">{$reviews['rev_msg']}</p>
                                    <footer class=\"blockquote-footer fs-6\">";
                                    if($reviews['rev_num']==1)
                                        echo "{$reviews['rev_num']} star ";
                                    else 
                                        echo "{$reviews['rev_num']} stars ";
                                    echo "</footer>
                                </blockquote>
                            </div>
                        </div>";
                }
            }else{
                echo "<p class=\"text-center\">No Reviews.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php
    include('includes/footer.php');
?>