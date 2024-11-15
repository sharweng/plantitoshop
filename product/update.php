<?php
    session_start();
    include('../includes/config.php');
    include('../includes/adminRedirect.php');
    
    $_SESSION['desc'] = trim($_POST['description']);
    $_SESSION['prc'] = trim($_POST['price']);
    $_SESSION['qty'] = $_POST['quantity'];

    $u_id = $_SESSION['u_id'];
    $_SESSION['u_id'] = $u_id;

    if (isset($_POST['submit'])) {
        $cat = $_POST['category'];

        if(empty($_POST['description'])){
            $_SESSION['descError'] = 'Error: please enter a product name.';
            header("Location: edit.php");
        }else{
            $desc = trim($_POST['description']);
            if(!preg_match("/^[a-zA-Z0-9\s\-_]{1,50}$/", $desc)){
                $_SESSION['descError'] = 'Error: must only contain up to 50 letters, numbers, spaces, hyphens, and underscores.';
                header("Location: edit.php");
            }
        }
        
        if(empty($_POST['price'])){
            $_SESSION['prcError'] = 'Error: please enter a price.';
            header("Location: edit.php");
        }else{
            $prc =  trim($_POST['price']);
            if(!preg_match("/^(|[1-9]\d*)(\.\d{1,2})?$/", $prc)){
                $_SESSION['prcError'] = 'Error: must be a valid number, and up to 2 decimal places only.';
                header("Location: edit.php");
            }
        }

        if(empty($_POST['quantity'])){
            $_SESSION['qtyError'] = 'Error: please enter a quantity.';
            header("Location: edit.php");
        }else{
            $qty = $_POST['quantity'];
            if(!preg_match("/^[1-9]\d*$/", $qty)){
                $_SESSION['qtyError'] = 'Error: must be a positive whole number.';
                header("Location: edit.php");
            }
        }

        if((preg_match("/^[a-zA-Z0-9\s\-_]{1,50}$/", $desc))&&(preg_match("/^(0|[1-9]\d*)(\.\d{1,2})?$/", $prc))
        &&(preg_match("/^[1-9]\d*$/", $qty))){
            $sql = "UPDATE product SET description = '{$desc}', price = '{$prc}', cat_id = '{$cat}' WHERE prod_id = {$u_id}";
            $result = mysqli_query($conn, $sql);

            $q_stock = "UPDATE stock SET quantity = {$qty} WHERE prod_id = {$u_id}";
            $result2 = mysqli_query($conn, $q_stock);
            
            if(!empty($_FILES['img_path']['name'][0])){
                $sql = "SELECT img_id, img_path FROM image WHERE prod_id = {$u_id}";
                $result4 = mysqli_query($conn, $sql);
                $existingImages = mysqli_fetch_all($result4, MYSQLI_ASSOC);
                $existingCount = count($existingImages);
                $uploadedFiles = $_FILES['img_path']['name'];
                $uploadCount = count($uploadedFiles);

                echo $uploadCount . " " . $existingCount . "<br>";;
                $counter = 0;
                foreach ($uploadedFiles as $key => $name) {
                    $source = $_FILES['img_path']['tmp_name'][$key];
                    $target = 'images/' . basename($name);

                    if (move_uploaded_file($source, $target)) {
                        if($counter < $existingCount){
                            $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$existingImages[$counter]['img_id']}";
                        }elseif ($counter < $uploadCount) {
                            $img_sql = "INSERT INTO image (prod_id, img_path) VALUES ({$u_id}, '{$target}')";
                        }else{
                            $img_sql = "DELETE FROM image WHERE img_id = {$existingImages[$counter]['img_id']}";
                        }

                        echo $img_sql . "<br>";
                        $result3 = mysqli_query($conn, $img_sql);

                        if(!$result3)
                            $isSuccess = false;
                    }
                    $counter++;
                    echo $counter . "<br>";
                }
                if ($uploadCount < $existingCount) {
                    for ($i = $uploadCount; $i < $existingCount; $i++) {
                        $delete_sql = "DELETE FROM image WHERE img_id = {$existingImages[$i]['img_id']}";
                        echo $delete_sql . "<br>";
                        $result5 = mysqli_query($conn, $delete_sql);
                        if(!$result5)
                            $isSuccess = false;
                    }
                }
            }
            
            if($result && $result2) {
                $_SESSION['desc'] = "";
                $_SESSION['prc'] = "";
                header("Location: index.php");
            }
        }
        
    }
?>
