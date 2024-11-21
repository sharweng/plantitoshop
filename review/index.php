<?php
    session_start();
    include('../includes/config.php');
    include('../includes/headerBS.php');
    include('../includes/notAdminRedirect.php');

    if(isset($_GET['email-search']))
        $keyword1 = strtolower(trim($_GET['email-search']));
    else
        $keyword1 = "";

    $email_sql = "SELECT user_id, email FROM user";
    if($keyword1){
        $email_sql = $email_sql . " WHERE email LIKE '%{$keyword1}%'";  
    }
    $email_query = mysqli_query($conn, $email_sql);


    if(isset($_GET['prod-search']))
        $keyword2 = strtolower(trim($_GET['prod-search']));
    else
        $keyword2 = "";

    $prod_sql = "SELECT p.prod_id, p.description, c.description as cat, p.price, s.quantity FROM product p INNER JOIN stock s ON p.prod_id = s.prod_id INNER JOIN category c ON p.cat_id = c.cat_id";
    if($keyword2){
        $prod_sql = $prod_sql . " WHERE p.description LIKE '%{$keyword2}%'";  
    }
    $prod_query = mysqli_query($conn, $prod_sql);

    if(isset($_GET['search']))
        $keyword3 = strtolower(trim($_GET['search']));
    else
        $keyword3 = "";

    $query = "SELECT r.rev_id, r.user_id, CONCAT(u.lname,', ', u.fname) as uname, u.email, p.prod_id, r.rev_num, p.description, r.rev_msg FROM review r
    INNER JOIN user u ON r.user_id = u.user_id INNER JOIN product p ON p.prod_id = r.prod_id";
    
    if(isset($_POST['view']) || isset($_POST['edit'])){
        $_SESSION['rev_id'] = $_POST['rev_id'];
    }

    if(isset($_SESSION['rev_id'])){
        $select_sql = $query . " WHERE rev_id = {$_SESSION['rev_id']}";
        $select_query = mysqli_query($conn, $select_sql);
        $select = mysqli_fetch_assoc($select_query);
    }
    
    if($keyword3){
        $query = $query . " WHERE CONCAT(u.lname,', ', u.fname) LIKE '%{$keyword3}%'";  
    }
    $result = mysqli_query($conn, $query);

    function truncateText($text, $maxLength = 30) {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . "...";
        }
        return $text;
    }

    if(isset($_POST['close'])){
        header("Location: /plantitoshop/review/");
        exit();
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <form action="" method="post" class="d-flex w-100 flex-flow-reverse align-items-center">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">New Review</h1>
                        <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close" name="close"></button>
                    </form>
                </div>
                <div class="modal-body">
                    <form action="" method="get" class="row">
                        <label for="user_id" class="form-label my-1">Search Email:</label>
                        <div class="input-group ">
                            <input type="text" class="form-control" name="email-search">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </form>
                    <form action="" method="get" class="row">
                        <label for="user_id" class="form-label my-1">Search Product:</label>
                        <div class="input-group ">
                            <input type="text" class="form-control" name="prod-search">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </form>
                    <form action="/plantitoshop/review/store.php" method="post">
                        <label for="user_id" class="form-label my-1">Email:</label>
                        <select class="form-select" name="email">
                            <?php
                            while($emails = mysqli_fetch_array($email_query)){
                                echo "<option value=\"{$emails['user_id']}\">{$emails['email']}</option>";
                            }
                            ?>
                        </select>
                        <label for="user_id" class="form-label my-1">Product:</label>
                        <select class="form-select" name="prod">
                            <?php
                                while($products = mysqli_fetch_array($prod_query)){
                                    echo "<option value=\"{$products['prod_id']}\">{$products['description']} / {$products['cat']} / &#x20B1;{$products['price']} / {$products['quantity']}</option>";
                                }
                            ?>
                        </select>
                        <label for="message-text" class="col-form-label">Rating:</label>
                        <select name="rev_num" class="form-select">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text" name="rev_msg"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="create_send">Send</button>
                </div>
                </form>           
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <form action="" method="post" class="d-flex w-100 flex-flow-reverse align-items-center">
                        <h1 class="modal-title fs-5 fw-bold text-truncate" id="exampleModalLabel"><?php echo $select['rev_msg'] ?></h1>
                        <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close" name="close"></button>
                    </form>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2 fw-bold">Email:</div>
                        <div class="col"><?php echo $select['email']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2 fw-bold">Customer:</div>
                        <div class="col"><?php echo $select['uname']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2 fw-bold">Product:</div>
                        <div class="col"><?php echo $select['description']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2 fw-bold">Rating:</div>
                        <div class="col"><?php echo $select['rev_num']; ?>/5</div>
                    </div>
                    <div class="mt-3 fw-bold">Message:</div>
                    <div style="text-align: justify;"><?php echo $select['rev_msg']; ?></div>
                </div>
                <div class="modal-footer">
                </div>      
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <form action="" method="post" class="d-flex w-100 flex-flow-reverse align-items-center">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Edit Review</h1>
                        <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close" name="close"></button>
                    </form>
                </div>
                <div class="modal-body">
                    <form action="" method="get" class="row">
                        <label for="user_id" class="form-label my-1">Search Email:</label>
                        <div class="input-group ">
                            <input type="text" class="form-control" name="email-search2">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </form>
                    <form action="" method="get" class="row">
                        <label for="user_id" class="form-label my-1">Search Product:</label>
                        <div class="input-group ">
                            <input type="text" class="form-control" name="prod-search2">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </form>
                    <form action="/plantitoshop/review/update.php" method="post">
                        <label for="user_id" class="form-label my-1">Email:</label>
                        <select class="form-select" name="email">
                            <?php
                            $email_sql_edit = "SELECT user_id, email FROM user";

                            if(isset($_GET['email-search2']))
                                $keyword1 = strtolower(trim($_GET['email-search2']));
                            else
                                $keyword1 = "";

                            if($keyword1){
                                $email_sql_edit = $email_sql_edit . " WHERE email LIKE '%{$keyword1}%'";  
                            }

                            $email_query_edit = mysqli_query($conn, $email_sql_edit);
                            while($emails = mysqli_fetch_array($email_query_edit)) {
                                $selected = ($emails['user_id'] == $select['user_id']) ? "selected" : "";
                                echo "<option value=\"{$emails['user_id']}\" {$selected}>{$emails['email']}</option>";
                            }
                            ?>
                        </select>
                        <label for="user_id" class="form-label my-1">Product:</label>
                        <select class="form-select" name="prod">
                            <?php
                                $prod_sql_edit = "SELECT p.prod_id, p.description, c.description as cat, p.price, s.quantity 
                                FROM product p 
                                INNER JOIN stock s ON p.prod_id = s.prod_id 
                                INNER JOIN category c ON p.cat_id = c.cat_id";
                        
                                if(isset($_GET['prod-search2']))
                                    $keyword2 = strtolower(trim($_GET['prod-search2']));
                                else
                                    $keyword2 = "";
                            
                                if($keyword2){
                                    $prod_sql_edit = $prod_sql_edit . " WHERE p.description LIKE '%{$keyword2}%'";  
                                }
                                $prod_query_edit = mysqli_query($conn, $prod_sql_edit);
                                while($products = mysqli_fetch_array($prod_query_edit)) {
                                    $selected = ($products['prod_id'] == $select['prod_id']) ? "selected" : "";
                                    echo "<option value=\"{$products['prod_id']}\" {$selected}>{$products['description']} / {$products['cat']} / &#x20B1;{$products['price']} / {$products['quantity']}</option>";
                                }
                           ?>
                        </select>
                        <label for="message-text" class="col-form-label">Rating:</label>
                        <select name="rev_num" class="form-select">
                            <?php
                                for($i = 1; $i <= 5; $i++){
                                    if($i == $select['rev_num'])
                                        echo "<option selected value=\"{$i}\">{$i}</option>";
                                    else
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                }
                            ?>
                        </select>
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text" name="rev_msg"><?php echo $select['rev_msg']; ?></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="create_send">Update</button>
                </div>
                </form>           
                </div>
            </div>
        </div>
    </div>
    <h1 class="text-center p-2 fw-bold">Reviews</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">ADD</button>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
                <form action="" method="get" class="d-inline-block">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search">
                        <button class="btn btn-success">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container inner-box border border-success border-2">
            <?php if($result->num_rows!=0){ ?>
            <table class="table mt-2">
                <thead class="text-center">
                    <tr>
                        <th>RevID</th>
                        <th>UID</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-wrap">
                            <td class="text-center align-middle"><?php echo $row['rev_id']; ?></td>
                            <td class="text-center align-middle"><?php echo $row['uname']; ?></td>
                            <td class="text-center align-middle"><?php echo $row['description']; ?></td>
                            <td class="align-middle"><?php echo truncateText($row['rev_msg']); ?></td>
                            <td>
                                <div class="row d-grid gap-1">
                                <div class="dropdown d-block\">
                                        <button class="btn btn-warning btn-sm w-100 dropdown-toggle\" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            OPTIONS
                                        </button>
                                        <ul class="dropdown-menu">
                                            <form action="" method="post">
                                                <input type="number" hidden name="rev_id" value="<?php echo $row['rev_id']; ?>">
                                                <button type="submit" class="dropdown-item btn-sm w-100" name="view" data-bs-toggle="modal" data-bs-target="#exampleModal2" value="<?php echo $row['rev_id']; ?>">VIEW</button>
                                                <button type="submit" class="dropdown-item btn-sm w-100" name="edit" data-bs-toggle="modal" data-bs-target="#exampleModal3" value="<?php echo $row['rev_id']; ?>">EDIT</button>
                                            </form>   
                                        </ul>
                                    </div>
                                    <div class="dropdown d-block\">
                                        <button class="btn btn-danger btn-sm w-100 dropdown-toggle\" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            DELETE
                                        </button>
                                        <ul class="dropdown-menu">
                                            <form action="delete.php" method="post">
                                                <button class="dropdown-item btn-sm w-100" name="delete" value="<?php echo $row['rev_id']; ?>">YES</button>
                                            </form>
                                            <form action="" method="post">
                                                <button class="dropdown-item btn-sm w-100" name="no" value="<?php echo $row['rev_id']; ?>">NO</button>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }else{echo "<p class=\"text-center mt-2 fw-bold\">No reviews found.</p>";} ?>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // Function to handle modal persistence for a specific modal
        function handleModalPersistence(modalId) {
            const modalElement = document.getElementById(modalId);
            const modal = new bootstrap.Modal(modalElement);

            // Check if the specific modal was open before refresh
            if (localStorage.getItem(`${modalId}Open`) === 'true') {
                modal.show();
            }

            // Listen for modal open/close events
            modalElement.addEventListener('show.bs.modal', function () {
                localStorage.setItem(`${modalId}Open`, 'true');
            });

            modalElement.addEventListener('hide.bs.modal', function () {
                localStorage.removeItem(`${modalId}Open`);
            });
        }

        // Apply persistence handling for each modal
        handleModalPersistence('exampleModal');
        handleModalPersistence('exampleModal2');
        handleModalPersistence('exampleModal3');

        // Function to reset modal fields on form submission
        function resetModalFields(modalId) {
            const modalElement = document.getElementById(modalId);
            const submitButton = modalElement.querySelector('[name="create_send"]');

            if (submitButton) {
                submitButton.addEventListener('click', function() {
                    setTimeout(function() {
                        modalElement.querySelectorAll('input, textarea, select').forEach(field => {
                            field.value = '';
                        });

                        // Optional: Hide the modal after resetting
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        modal.hide();
                    }, 100); // Ensure reset after form submission
                });
            }
        }

        // Apply field reset for each modal
        resetModalFields('exampleModal');
        resetModalFields('exampleModal2');
        resetModalFields('exampleModal3');
    });

    </script>
</body>
</html>
<?php
    include('../includes/footer.php');
?>