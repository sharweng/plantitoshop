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
    <title>Products</title>
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
                                    <input type=\"hidden\" name=\"type\" value=\"add\" />
                                    <button class=\"btn btn-success w-100 form-btn my-2\" name=\"add_to_cart\">ADD TO CART</button>
                                </form>
                            </div>";
                    }
                    ?>
            </div>
            
        </div>
    </div>
</body>
</html>
<?php
    include('includes/footer.php');
?>