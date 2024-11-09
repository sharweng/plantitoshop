<?php
    session_start();
    include('../includes/config.php');
    if($_SESSION['isAdmin'] == true)
        include('../includes/adminHeader.php');
    else
        include('../includes/header.php');

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    if($keyword){
        $sql = "SELECT p.prod_id, p.description, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id WHERE description LIKE '%{$keyword}%'";
        $result = mysqli_query($conn, $sql);
    }else{
        $sql = "SELECT p.prod_id, p.description, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id";
        $result = mysqli_query($conn, $sql);
    }
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
    <h1 class="text-center p-2">This is the product page.</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-2 d-flex align-items-center justify-content-start">
                <a href="create.php">
                    <button class="btn btn-success">ADD</button>
                </a>
            </div>
            <div class="col-4 d-flex align-items-center justify-content-end">
                <form action="" method="get">
                    <div class="row d-grid gap-1">
                        <div class="d-block">
                            <input type="text" name="search">
                        </div>
                        <div class="d-block">
                            <button class="btn btn-success w-100 btn-sm">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="container inner-box border border-success border-2">
        <?php 
            while($row = mysqli_fetch_array($result)){
                echo "
                    <table class=\"table text-center \">
                        <tr class=\"details row d-grid\">
                            <td class=\"col d-flex flex-wrap align-items-center justify-content-center\">";
                $sql = "SELECT img_path FROM image WHERE prod_id = {$row['prod_id']}";
                $result2 = mysqli_query($conn, $sql);
                while($row2 = mysqli_fetch_array($result2)){
                    echo "<img class=\"img-thumbnail\" src=\"{$row2['img_path']}\" height=\"100px\" width=\"100px\">";
                }
                            echo "</td>
                            <td class=\"col d-flex align-items-center\">
                                <div class=\"col d-grid align-items-center\">{$row['prod_id']}</div>
                                <div class=\"col d-grid align-items-center\">{$row['description']}</div>
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