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
    <style>
        <?php include('../includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1>This is the product page.</h1>
    <a href="create.php">
        <button class="add-button">ADD</button>
    </a>
    <form action="" method="get">
        <input type="text" name="search">
        <button>Search</button>
    </form>
    <div class="container">
    <?php 
        while($row = mysqli_fetch_array($result)){
            echo "
                <table>
                    <tr class=\"details\">
                        <td>{$row['prod_id']}</td>
                        <td>";
            $sql = "SELECT img_path FROM image WHERE prod_id = {$row['prod_id']}";
            $result2 = mysqli_query($conn, $sql);
            while($row2 = mysqli_fetch_array($result2)){
                echo "<img src=\"{$row2['img_path']}\" height=\"100px\" width=\"100px\">";
            }
                        echo "</td>
                        <td>{$row['description']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['quantity']}</td>
                        <td>
                            <form action=\"\" method=\"post\">
                                <button name=\"update_btn\" value=\"{$row['prod_id']}\">EDIT</button>
                            </form>";
            if(isset($_POST['update_btn'])&&!isset($_POST['no'])&&$_POST['update_btn']==$row['prod_id']){
                echo "<form action=\"edit.php\" method=\"post\">
                    <button name=\"update_id\" value=\"{$row['prod_id']}\">YES</button>
                </form>
                <form action=\"\" method=\"post\">
                    <button name=\"no\" value=\"{$row['prod_id']}\">NO</button>
                </form>";
            }
                            echo"<form action=\"\" method=\"post\">
                                <button name=\"delete_btn\" value=\"{$row['prod_id']}\">DELETE</button>
                            </form>";
            if(isset($_POST['delete_btn'])&&!isset($_POST['no'])&&$_POST['delete_btn']==$row['prod_id']){
                echo "<form action=\"delete.php\" method=\"post\">
                    <button name=\"delete_id\" value=\"{$row['prod_id']}\">YES</button>
                </form>
                <form action=\"\" method=\"post\">
                    <button name=\"no\" value=\"{$row['prod_id']}\">NO</button>
                </form>";
            }
            echo"</td>
                    </tr>
                </table>
            ";
        }
    ?>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>