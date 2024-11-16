<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    $sql = "SELECT p.prod_id, p.description, p.price, s.quantity, c.description as cat FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id";
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
    <title>Products</title>
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
    <h1 class="text-center p-2 fw-bold">Products</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="create.php">
                    <button class="btn btn-success">ADD</button>
                </a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Category
                    </button>
                    <div class="dropdown-menu">
                        <form action="" method="post">
                            <button class="dropdown-item" name="none">None</button>
                            <button class="dropdown-item" name="herbs">Herbs</button>
                            <button class="dropdown-item" name="shrubs">Shrubs</button>
                            <button class="dropdown-item" name="creepers">Creepers</button>
                            <button class="dropdown-item" name="climbers">Climbers</button>
                            <button class="dropdown-item" name="miscs">Miscellaneous</button>
                        </form>
                    </div>
                </div>
                <form action="" method="get" class="d-inline-block">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search">
                        <button class="btn btn-success">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "
                    <table class=\"table text-center \">
                        <tr class=\"details row d-grid\">
                            <td class=\"col d-flex flex-wrap align-items-center justify-content-center\">";
                $sql = "SELECT img_path FROM image WHERE prod_id = {$row['prod_id']}";
                $result2 = mysqli_query($conn, $sql);
                while($row2 = mysqli_fetch_array($result2)){
                    echo "<img class=\"img-thumbnail image-admin-css\" src=\"{$row2['img_path']}\" height=\"100px\" width=\"100px\">";
                }
                            echo "</td>
                            <div class=\"col d-grid align-items-center fw-bold justify-content-center text-wrap\">{$row['description']}</div>
                            <td class=\"col d-flex align-items-center\">
                                <div class=\"col d-grid align-items-center\">{$row['prod_id']}</div>
                                <div class=\"col d-grid align-items-center text-wrap\">{$row['cat']}</div>
                                <div class=\"col d-grid align-items-center\">{$row['price']}</div>
                                <div class=\"col d-grid align-items-center\">{$row['quantity']}</div>
                                <div class=\"col d-grid align-items-center\">
                                    <div class=\"row d-grid gap-1\">
                                        <form action=\"edit.php\" method=\"post\">
                                            <button class=\"btn btn-warning btn-sm w-100\" name=\"update_id\" value=\"{$row['prod_id']}\">EDIT</button>
                                        </form>
                                        <div class=\"dropdown d-block\">
                                            <button class=\"btn btn-danger btn-sm w-100 dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                                DELETE
                                            </button>
                                            <ul class=\"dropdown-menu\">
                                                <form action=\"delete.php\" method=\"post\">
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"delete_id\" value=\"{$row['prod_id']}\">YES</button>
                                                </form>
                                                <form action=\"\" method=\"post\">
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"no\" value=\"{$row['prod_id']}\">NO</button>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                        </tr>
                    </table>
                ";
            }
        ?>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>