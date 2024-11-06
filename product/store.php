<?php
    session_start();
    include('../includes/config.php');
    $_SESSION['desc'] = trim($_POST['description']);
    $_SESSION['prc'] = trim($_POST['price']);
    $_SESSION['cat'] = $_POST['category'];
    $_SESSION['qty'] = $_POST['quantity'];


    if (isset($_POST['submit'])) {
        $desc = trim($_POST['description']);
        $prc =  trim($_POST['price']);
        $qty = $_POST['quantity'];
        $cat = $_POST['category'];

        if(empty($_POST['description'])){
            $_SESSION['descError'] = '<label>Error: please input a PRODUCT DESCRIPTION.</label><br>';
            header("Location: create.php");
        }
        if(empty($_POST['quantity']) || (! is_numeric($qty))){
            $_SESSION['qtyError'] = '<label>Error: wrong QUANTITY FORMAT.</label><br>';
            header("Location: create.php");
        }
        if(empty($_POST['price']) || (! is_numeric($prc))){
            $_SESSION['prcError'] = '<label>Error: wrong product PRICE FORMAT.</label><br>';
            header("Location: create.php");
        }
        if(!isset($_FILES['img_path']) && empty($_FILES['img_path']['name'][0])){
            $_SESSION['imgError'] = '<label>Error: upload atleast one FILE.</label><br>';
            header("Location: create.php");
        }

        if(!empty($_POST['description']) && !empty($_POST['price'])
        && (is_numeric($prc)) && !empty($_POST['quantity'])
        &&(isset($_FILES['img_path']) && !empty($_FILES['img_path']['name'][0]))){
            // PRODUCT INSERT
            $sql = "INSERT INTO product(description, price, cat_id) VALUES('{$desc}', '{$prc}', '{$cat}')";
            $result = mysqli_query($conn, $sql);

            $getLastId = "SELECT MAX(prod_id) as max FROM product";
            $result = mysqli_query($conn, $getLastId);
            $row = mysqli_fetch_array($result);
            $last_id = $row['max'];

            // STOCK INSERT
            $q_stock = "INSERT INTO stock(prod_id, quantity) VALUES(LAST_INSERT_ID(), {$qty})";
            $result2 = mysqli_query($conn, $q_stock);


            // FILES INSERT AND MOVE
            $isSuccess = true;
            foreach($_FILES['img_path']['name'] as $key => $name){
                $source = $_FILES['img_path']['tmp_name'][$key];
                $target = 'images/' . basename($name);

                if (move_uploaded_file($source, $target)) {
                    $img_sql = "INSERT INTO image(prod_id, img_path) VALUES({$last_id}, '{$target}')";
                    $result3 = mysqli_query($conn, $img_sql);

                    if($isSuccess == false)
                        $isSuccess = false;
                    if(!$result3)
                        $isSuccess = false;     
                } else {
                    $_SESSION['imgError'] = "Error: couldn't copy the image file.";
                    header("Location: create.php");
                }
            }

            if($result && $result2 && $isSuccess) {
                $_SESSION['desc'] = "";
                $_SESSION['prc'] = "";
                header("Location: index.php");
            }
        }
        
    }
?>