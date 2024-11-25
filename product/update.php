<?php
    session_start();
    include('../includes/config.php');
    include('../includes/notAdminRedirect.php');

    $_SESSION['desc'] = trim($_POST['description']);
    $_SESSION['prc'] = trim($_POST['price']);
    $_SESSION['qty'] = $_POST['quantity'];
    $_SESSION['defi'] = trim($_POST['definition']);

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

        if(empty($_POST['definition'])){
            $_SESSION['defiError'] = 'Error: please enter a product definition.';
            header("Location: edit.php");
        }else{
            $defi = trim($_POST['definition']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\-:;!?]{10,255}$/", $defi)){
                $_SESSION['defiError'] = 'Error: must only contain only alphanumeric and puntuation characters, min 10 characters.';
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
        &&(preg_match("/^[1-9]\d*$/", $qty))&&((preg_match("/^[a-zA-Z0-9\s.,'\"\-:;!?]{10,255}$/", $defi)))){

            $badWords = ['putangina', "putang ina", 'gago', 'tanga', 'ulol', 'bobo', 'lintek', 'yawa', 'pokpok', 'tarantado',
                        'inamo', 'pucha', 'putcha', 'puta', 'gagi', 'idiot', 'moron', 'stupid', 'bitch', 'ass',
                        'jerk', 'loser', 'slut', 'whore', 'asshole', 'bastard', 'fuck', 'dick', 'burat', 'bayag',
                        'inutil', 'nigger', 'nigga', 'cunt', 'dumbass', 'fucker', 'shithead', 'douchebag', 'retard', 'faggot',
                        'douche', 'jackass', 'bayot', 'pakshet', 'bwisit', 'leche', 'gaga', 'buang', 'boang', 'putragis', 'kupal',
                        'punyeta', 'shet', 'tangina', 'pakyu'];
            $pattern = '/\b(' . implode('|', array_map('preg_quote', $badWords)) . ')\b/i';
            $maskedMessage = preg_replace_callback($pattern, function($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $defi);

            $sql = "UPDATE product SET description = ?, price = ?, definition = ?, cat_id = ? WHERE prod_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $desc, $prc, $maskedMessage, $cat, $u_id);
            $result = $stmt->execute();

            $q_stock = "UPDATE stock SET quantity = {$qty} WHERE prod_id = {$u_id}";
            $result2 = mysqli_query($conn, $q_stock);
            
            if(!empty($_FILES['img_path']['name'][0])){
                $sql = "SELECT img_id, img_path FROM image WHERE prod_id = {$u_id}";
                $result4 = mysqli_query($conn, $sql);
                $existingImages = mysqli_fetch_all($result4, MYSQLI_ASSOC);
                $existingCount = count($existingImages);
                $uploadedFiles = $_FILES['img_path']['name'];
                $uploadCount = count($uploadedFiles);

                $counter = 0;
                foreach ($uploadedFiles as $key => $name) {
                    if ($uploadCount < $existingCount) {
                        for ($i = 0; $i < $existingCount; $i++) {
                            $sql = "SELECT img_path FROM image WHERE img_id = {$existingImages[$i]['img_id']}";
                            $result2 = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result2)){
                                unlink($row['img_path']);
                            }
                        }
                        for ($i = $uploadCount; $i < $existingCount; $i++) {
                            $sql = "SELECT img_path FROM image WHERE img_id = {$existingImages[$i]['img_id']}";
                            $result2 = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result2)){
                                unlink($row['img_path']);
                            }
                            $delete_sql = "DELETE FROM image WHERE img_id = {$existingImages[$i]['img_id']}";
                            $result5 = mysqli_query($conn, $delete_sql);
                            if(!$result5)
                                $isSuccess = false;
                        }
                    }
                }
                
                foreach ($uploadedFiles as $key => $name) {
                    $source = $_FILES['img_path']['tmp_name'][$key];
                    
                    switch($cat){
                        case 1:
                            $target = 'images/Miscellaneous/' . basename($name);
                            break;
                        case 2:
                            $target = 'images/Herbs/' . basename($name);
                            break;
                        case 3:
                            $target = 'images/Shrubs/' . basename($name);
                            break;
                        case 4:
                            $target = 'images/Creepers/' . basename($name);
                            break;
                        case 5:
                            $target = 'images/Climbers/' . basename($name);
                            break;
                        default:
                            $target = 'images/' . basename($name);    
                    }

                    if (move_uploaded_file($source, $target)) {
                        if($counter < $existingCount){
                            $sql = "SELECT img_path FROM image WHERE img_id = {$existingImages[$counter]['img_id']}";
                            $deleteresult = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($deleteresult)){
                                unlink($row['img_path']);
                            }
                            $sql = "UPDATE image SET img_path = '' WHERE img_id = {$existingImages[$counter]['img_id']}";
                            $result2 = mysqli_query($conn, $sql);
                            $img_sql = "UPDATE image SET img_path = '{$target}' WHERE img_id = {$existingImages[$counter]['img_id']}";
                        }elseif ($counter < $uploadCount) {
                            $img_sql = "INSERT INTO image (prod_id, img_path) VALUES ({$u_id}, '{$target}')";
                        }else{
                            $sql = "SELECT img_path FROM image WHERE img_id = {$existingImages[$counter]['img_id']}";
                            $result2 = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result2)){
                                unlink($row['img_path']);
                            }
                            $img_sql = "DELETE FROM image WHERE img_id = {$existingImages[$counter]['img_id']}";
                        }

                        echo $img_sql . "<br>";
                        $result3 = mysqli_query($conn, $img_sql);
                        if($result3)
                            echo "Success<br>";

                        if(!$result3)
                            $isSuccess = false;
                    }
                    $counter++;
                    echo $counter . "<br>";
                }            
            }
            
            if($result && $result2) {
                $_SESSION['desc'] = "";
                $_SESSION['prc'] = "";
                $_SESSION['defi'] = "";
                header("Location: index.php");
            }
        }
        
    }
?>
