<?php
    session_start();
    include('includes/config.php');
    include('includes/headerBS.php');

    if(isset($_POST['view'])){
        $_SESSION['prod_id'] = $_POST['prod_id'];
        header("Location: view_product.php");
    }       

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    $sql = "SELECT p.prod_id, p.description, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id";
    if($keyword){
        $sql = $sql . " WHERE p.description LIKE '%{$keyword}%'";  
    }
    if(isset($_POST['miscs'])){
        $sql = $sql . " WHERE c.cat_id = 1";  
    }elseif(isset($_POST['herbs'])){
        $sql = $sql . " WHERE c.cat_id = 2";  
    }elseif(isset($_POST['shrubs'])){
        $sql = $sql . " WHERE c.cat_id = 3";  
    }elseif(isset($_POST['creepers'])){
        $sql = $sql . " WHERE c.cat_id = 4";  
    }elseif(isset($_POST['climbers'])){
        $sql = $sql . " WHERE c.cat_id = 5";    
    }
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
    </style>
</head>
<body>
    <div class="container">
        <div class="container d-flex justify-content-center">
            <?php 
                $picture_sql = "SELECT i.img_path FROM image i INNER JOIN product p ON p.prod_id = i.prod_id WHERE p.cat_id != 1 ORDER BY RAND()";
                //$picture_sql = "SELECT img_path FROM image WHERE img_id = 200";
                $picture_query = mysqli_query($conn, $picture_sql);
                if($picture_query->num_rows != 0){ ?>
            <div class="slideshow">
                <!-- Slide container -->
                <div class="slides">
                    <?php
                        
                        while($pictures = mysqli_fetch_array($picture_query)){
                            echo "<div class=\"slide\"><img src=\"/plantitoshop/product/{$pictures['img_path']}\"></div>";
                        } 
                    ?>
                </div>
                <div class="overlay-text fw-bold">Plantito's Shop</div>
            </div>
            <?php }else{
                    echo "<h1 class=\"text-center p-2 fw-bold\">Plantito's Shop</h1>";
                }
            ?>
        </div>
        <div class="container d-flex justify-content-center py-2 gap-1">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Category
                </button>
                <div class="dropdown-menu">
                    <form action="" method="post">
                        <button class="dropdown-item" name="none">All</button>
                        <button class="dropdown-item" name="herbs">Herbs</button>
                        <button class="dropdown-item" name="shrubs">Shrubs</button>
                        <button class="dropdown-item" name="creepers">Creepers</button>
                        <button class="dropdown-item" name="climbers">Climbers</button>
                        <button class="dropdown-item" name="miscs">Miscellaneous</button>
                    </form>
                </div>
            </div>
            <div class="d-flex gap-1">
                <form action="" method="get" class="d-inline-block">
                    <div class="input-group index_search">
                        <input type="text" class="form-control" name="search">
                        <button class="btn btn-success">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container d-flex flex-wrap justify-content-center gap-3">
            <?php
            if($result->num_rows != 0){
                while($row = mysqli_fetch_array($result)){
                    echo "<div class=\"card enlarge p-1\" style=\"width: 230px;\">";
                    $sql = "SELECT img_path FROM image WHERE prod_id = {$row['prod_id']}";
                    $result2 = mysqli_query($conn, $sql);
                    while($row2 = mysqli_fetch_array($result2)){
                        echo "<img src=\"../plantitoshop/product/{$row2['img_path']}\" class=\"card-img-top\" style=\"width: 220px; height: 220px; object-fit: cover;\">";
                        break;
                    }
                    echo "<div class=\"card-body \">
                                <h5 class=\"card-title fw-bold text-truncate\">{$row['description']}</h5>
                                <p class=\"card-text\">Price: &#x20B1;{$row['price']}<br>Stock: {$row['quantity']}</p>
                                <div class=\"d-flex gap-1\">
                                    <form action=\"\" method=\"post\" class=\"col\">
                                    <input type=\"hidden\" name=\"prod_id\" value=\"{$row['prod_id']}\" />
                                        <button class=\"col btn btn-success w-100 btn-sm\" name=\"view\">View Detail</button>
                                    </form>
                                    <form action=\"cart_update.php\" method=\"post\" class=\"col\">
                                        <input type=\"hidden\" name=\"prod_id\" value=\"{$row['prod_id']}\" />
                                        <input class=\"form-control d-inline\" type=\"hidden\" value=\"1\" placeholder=\"1\" name=\"quantity\" min=\"1\">
                                        <input class=\"form-control d-inline\" type=\"hidden\" value=\"{$row['quantity']}\" placeholder=\"1\" name=\"stock\" min=\"1\">
                                        <input type=\"hidden\" name=\"type\" value=\"add\" />
                                        <button class=\"col btn btn-success w-100 btn-sm\" name=\"add_cart\">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>";
                }
            }else{
                echo "<p class=\"text-center mt-2 fw-bold\">No results found.</p>";
            }
            ?>
        </div>
    </div>
    
        
</body>
</html>
<?php
    include('includes/footer.php');
?>