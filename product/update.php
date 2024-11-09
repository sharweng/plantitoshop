<?php
    session_start();
    include('../includes/config.php');
    $_SESSION['desc'] = trim($_POST['description']);
    $_SESSION['prc'] = trim($_POST['price']);
    $_SESSION['qty'] = $_POST['quantity'];

    $u_id = $_SESSION['u_id'];
    $_SESSION['u_id'] = $u_id;

    if (isset($_POST['submit'])) {
        $desc = trim($_POST['description']);
        $prc =  trim($_POST['price']);
        $qty = $_POST['quantity'];
        $cat = $_POST['category'];

        if(empty($_POST['description'])){
            $_SESSION['descError'] = '<label>Error: please input a PRODUCT DESCRIPTION.</label><br>';
            header("Location: edit.php");
        }
        if(empty($_POST['quantity']) || (! is_numeric($qty))){
            $_SESSION['qtyError'] = '<label>Error: wrong QUANTITY FORMAT.</label><br>';
            header("Location: edit.php");
        }
        if(empty($_POST['price']) || (! is_numeric($prc))){
            $_SESSION['prcError'] = '<label>Error: wrong product PRICE FORMAT.</label><br>';
            header("Location: edit.php");
        }
        
        

        if(!empty($_POST['description']) && !empty($_POST['price'])
        && (is_numeric($prc)) && !empty($_POST['quantity'])){
            $sql = "UPDATE product SET description = '{$desc}', price = '{$prc}', cat_id = '{$cat}' WHERE prod_id = {$u_id}";
            $result = mysqli_query($conn, $sql);

            $q_stock = "UPDATE stock SET quantity = {$qty} WHERE prod_id = {$u_id}";
            $result2 = mysqli_query($conn, $q_stock);

            
            
            if(isset($_FILES['img_path'])){
                $sql = "SELECT img_id, img_path FROM image WHERE prod_id = {$u_id}";
                $result = mysqli_query($conn, $sql);
                $existingImages = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $existingCount = count($existingImages);
                $uploadedFiles = $_FILES['img_path']['name'];
                $uploadCount = count($uploadedFiles);

                echo $uploadCount . " " . $existingCount . "<br>";;
                $counter = 0;
                foreach ($uploadedFiles as $key => $name) {
                    $source = $_FILES['img_path']['tmp_name'][$key];
                    $target = 'images/' . basename($name);

                    if (move_uploaded_file($source, $target)) {
                        if ($counter < $existingCount) {
                            $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$existingImages[$counter]['img_id']}";
                        } elseif ($counter < $uploadCount) {
                            $img_sql = "INSERT INTO image (prod_id, img_path) VALUES ({$u_id}, '{$target}')";
                        } else {
                            $img_sql = "DELETE FROM image WHERE img_id = {$existingImages[$counter]['img_id']}";
                        }

                        echo $img_sql . "<br>";
                        $result3 = mysqli_query($conn, $img_sql);

                        if (!$result3) {
                            $isSuccess = false;
                        }
                    }
                    $counter++;
                    echo $counter . "<br>";
                }
                /*
                $sql = "SELECT COUNT(img_path) as count FROM image WHERE prod_id = {$u_id}";
                $count_query = mysqli_query($conn, $sql);
                $numOfImg = mysqli_fetch_array($count_query);
                $count = $numOfImg['count'];

                $numFiles = count($_FILES['img_path']['name']);
                echo $numFiles . " " . $count . "<br>";
                $isSuccess = true;
                $counter = 0;
                foreach($_FILES['img_path']['name'] as $key => $name){
                    $source = $_FILES['img_path']['tmp_name'][$key];
                    $target = 'images/' . basename($name);

                    $sql = "SELECT * FROM image WHERE prod_id = {$u_id}";
                    echo $sql . "<br>";
                    $result4 = mysqli_query($conn, $sql);
                    if (move_uploaded_file($source, $target)) {
                        if($numFiles < $count){
                            while($row = mysqli_fetch_array($result4)){
                                if($counter < $numFiles){
                                    $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$row['img_id']}";
                                    echo $img_sql . "<br>";
                                    $result3 = mysqli_query($conn, $img_sql);
                                }else{
                                    $img_sql = "DELETE FROM image WHERE img_id = {$row['img_id']}";
                                    echo $img_sql . "<br>";
                                    $result3 = mysqli_query($conn, $img_sql);            
                                }
                                $counter++;
                                $counter . "<br>";
                            }    
                        }elseif($numFiles > $counter){
                            while($row = mysqli_fetch_array($result4)){
                                if($counter < ($numFiles-$count)){
                                    $img_sql = "INSERT INTO image(prod_id, img_path) VALUES({$u_id}, '{$target}')";
                                    echo $img_sql . "<br>";
                                    $result3 = mysqli_query($conn, $img_sql);
                                }else{
                                    $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$row['img_id']}";
                                    echo $img_sql . "<br>";
                                    $result3 = mysqli_query($conn, $img_sql);            
                                }
                                $counter++;
                                $counter . "<br>";
                            }  
                        }else{
                            while($row = mysqli_fetch_array($result4)){
                                $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$row['img_id']}";
                                echo $img_sql . "<br>";
                                $result3 = mysqli_query($conn, $img_sql);
                            }     
                        }
                        
                        if($isSuccess == false)
                            $isSuccess = false;
                        if(!$result3)
                            $isSuccess = false;     
                    }
                }
                    */
            }
            
            if($result && $result2) {
                $_SESSION['desc'] = "";
                $_SESSION['prc'] = "";
                //header("Location: index.php");
            }
        }
        
    }
?>
