<?php
    session_start();
    include('../includes/config.php');
    include('../includes/notAdminRedirect.php');

    $_SESSION['desc'] = trim($_POST['description']);
    $_SESSION['defi'] = trim($_POST['definition']);
    $_SESSION['prc'] = trim($_POST['price']);
    $_SESSION['cat'] = $_POST['category'];
    $_SESSION['qty'] = $_POST['quantity'];

    $_SESSION['descError'] = "";
    $_SESSION['prcError'] = "";
    $_SESSION['qtyError'] = "";
    $_SESSION['imgcError'] = "";
    $_SESSION['defiError'] = "";
    if (isset($_POST['submit'])) {
        $cat = $_POST['category'];

        if(empty($_POST['description'])){
            $_SESSION['descError'] = 'Error: please enter a product name.';
            header("Location: create.php");
        }else{
            $desc = trim($_POST['description']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\-_]{1,50}$/", $desc)){
                $_SESSION['descError'] = 'Error: must only contain up to 50 letters, numbers, spaces, hyphens, and underscores.';
                header("Location: create.php");
            }
        }
        
        if(empty($_POST['price'])){
            $_SESSION['prcError'] = 'Error: please enter a price.';
            header("Location: create.php");
        }else{
            $prc =  trim($_POST['price']);
            if(!preg_match("/^(|[1-9]\d*)(\.\d{1,2})?$/", $prc)){
                $_SESSION['prcError'] = 'Error: must be a valid number, and up to 2 decimal places only.';
                header("Location: create.php");
            }
        }

        if(empty($_POST['quantity'])){
            $_SESSION['qtyError'] = 'Error: please enter a quantity.';
            header("Location: create.php");
        }else{
            $qty = $_POST['quantity'];
            if(!preg_match("/^[1-9]\d*$/", $qty)){
                $_SESSION['qtyError'] = 'Error: must be a positive whole number.';
                header("Location: create.php");
            }
        }

        if(empty($_POST['definition'])){
            $_SESSION['defiError'] = 'Error: please enter a product definition.';
            header("Location: create.php");
        }else{
            $defi = trim($_POST['definition']);
            if(!preg_match("/^[a-zA-Z0-9\s.,'\"\-:;!?]{10,255}$/", $defi)){
                $_SESSION['defiError'] = 'Error: must only contain only alphanumeric and punctuation characters, min 10 characters.';
                header("Location: create.php");
            }
        }

        if(empty($_FILES['img_path']['name'][0])){
            $_SESSION['imgError'] = 'Error: upload atleast one file.';
            header("Location: create.php");
        }

        if((preg_match("/^[a-zA-Z0-9\s.,'\"\-_]{1,50}$/", $desc))&&(preg_match("/^(0|[1-9]\d*)(\.\d{1,2})?$/", $prc))
        &&(preg_match("/^[1-9]\d*$/", $qty))&&(!empty($_FILES['img_path']['name'][0])&&(preg_match("/^[a-zA-Z0-9\s.,'\"\-:;!?]{10,255}$/", $defi)))){

            $searchsql = "SELECT description FROM product";
            $prodExists = mysqli_query($conn, $searchsql);
            while($row = mysqli_fetch_array($prodExists)){
                if(strtolower($desc) == strtolower($row['description'])){
                    $_SESSION['message'] = 'This product already exists. Please use a different name or check the product list.';
                    header("Location: /plantitoshop/product/create.php");
                    exit();
                }
            }
            // PRODUCT INSERT

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

            $sql = "INSERT INTO product (description, price, definition, cat_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $desc, $prc, $maskedMessage, $cat);
            $result = $stmt->execute();

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
                $_SESSION['defi'] = "";
                header("Location: index.php");
            }
        }
        
    }
?>