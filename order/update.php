<?php
    session_start();
    include('../includes/config.php');

    if(isset($_POST['updateOD'])){
        $orderinfo_id = $_SESSION['view_id'];
        $user_id = $_POST['email'];
        $date_placed = $_POST['date_placed'];
        $stat_id = $_POST['stat_id'];
        $shipping = $_POST['shipping'];

        $sql_orderinfo = "UPDATE orderinfo SET user_id = '$user_id', date_placed = '$date_placed', stat_id = '$stat_id', shipping = '$shipping' WHERE orderinfo_id = $orderinfo_id";
        $result = mysqli_query($conn, $sql_orderinfo);

        if($stat_id != 1){
            $select_sql = "SELECT ol.orderinfo_id, ol.prod_id, p.description, ol.quantity FROM orderline ol INNER JOIN product p ON ol.prod_id = p.prod_id WHERE ol.orderinfo_id = {$_SESSION['view_id']}";
            $select_query = mysqli_query($conn, $select_sql);
            while($select = mysqli_fetch_array($select_query)){
                $stock_sql = "UPDATE stock SET quantity = (quantity + {$select['quantity']}) WHERE prod_id = {$select['prod_id']}";
                $stock_query = mysqli_query($conn, $stock_sql);
            }
        }

        if($result){
            header('Location: /plantitoshop/order/');
            exit();
        }else{
            $_SESSION['message'] = "Failed to create orderinfo.";
            header('Location: /plantitoshop/order/');
            exit();
        }
    }
    if(isset($_POST['updateOI'])){
        $oi_id = $_SESSION['view_id'];             
        $bfr_prod_id = $_SESSION['editprod_id'];    
        $prod_id = $_POST['prod'];                  
        $quantity = $_POST['quantity'];             
        $bfr_qty = $_POST['bfr_qty'];              

        $select_sql = "SELECT * FROM stock WHERE prod_id = $prod_id";
        $select_query = mysqli_query($conn, $select_sql);
        $select = mysqli_fetch_assoc($select_query);
        $stock_qty = $select['quantity'];

 
        if ($quantity > $stock_qty) { 
            $_SESSION['message'] = "Invalid quantity: The quantity you selected is higher than the available stock.";
            header("Location: editOI.php");
            exit();
        }

      
        $sql_orderline = "UPDATE orderline 
                        SET prod_id = $prod_id, quantity = $quantity 
                        WHERE orderinfo_id = $oi_id AND prod_id = $bfr_prod_id";
        $result = mysqli_query($conn, $sql_orderline);

      
        if ($prod_id != $bfr_prod_id) {
            $select_sql = "SELECT * FROM stock WHERE prod_id = $bfr_prod_id";
            $select_query = mysqli_query($conn, $select_sql);
            $select = mysqli_fetch_assoc($select_query);
            $bfr_stock_qty = $select['quantity'];

            $stock_sql = "UPDATE stock 
                        SET quantity = ($bfr_qty + $bfr_stock_qty) 
                        WHERE prod_id = $bfr_prod_id";
            $stock_query = mysqli_query($conn, $stock_sql);

            $stock_sql = "UPDATE stock 
                        SET quantity = (quantity - $quantity) 
                        WHERE prod_id = $prod_id";
            $stock_query = mysqli_query($conn, $stock_sql);
        }else{
            if($quantity < $bfr_qty){
                $stock_sql = "UPDATE stock 
                    SET quantity = (quantity + ($bfr_qty - $quantity)) 
                    WHERE prod_id = $prod_id";
                $stock_query = mysqli_query($conn, $stock_sql);
            }elseif($quantity > $bfr_qty){
                $stock_sql = "UPDATE stock 
                    SET quantity = (quantity - ($quantity - $bfr_qty)) 
                    WHERE prod_id = $prod_id";
                $stock_query = mysqli_query($conn, $stock_sql);
            }
            
        }


        

        if ($result) {
            header('Location: /plantitoshop/order/order_view.php');
            exit();
        } else {
            $_SESSION['message'] = "Failed to update orderline.";
            header('Location: /plantitoshop/order/order_view.php');
            exit();
        }
    }
?>
