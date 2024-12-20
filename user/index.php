<?php
    session_start();
    include('../includes/config.php');

    if(!isset($_SESSION['roleDesc'])){
        $_SESSION['roleDesc'] = "";
    }
    include('../includes/notAdminRedirect.php');
    include('../includes/headerBS.php');

    if(isset($_GET['search']))
        $keyword = strtolower(trim($_GET['search']));
    else
        $keyword = "";

    $sql = "SELECT u.user_id, u.lname, u.fname, u.email, u.addressline, u.phone, u.pfp_path, r.description FROM user u INNER JOIN role r ON u.role_id = r.role_id";
    if($keyword){
        $sql = $sql . " WHERE u.lname LIKE '%{$keyword}%'";
    }
    if(isset($_POST['admin'])){
        $sql = $sql . " WHERE r.description = 'admin'";  
    }elseif(isset($_POST['user'])){
        $sql = $sql . " WHERE r.description = 'user'";  
    }elseif(isset($_POST['deact'])){
        $sql = $sql . " WHERE r.description = 'deactivated'";  
    }
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
    <h1 class="text-center p-2 fw-bold">Users</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <form action="register.php" method="post">
                    <button class="btn btn-success" name="add_user">ADD</button>
                </form>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Role
                    </button>
                    <div class="dropdown-menu">
                        <form action="" method="post">
                            <button class="dropdown-item" name="none">None</button>
                            <button class="dropdown-item" name="admin">Admin</button>
                            <button class="dropdown-item" name="user">User</button>
                            <button class="dropdown-item" name="deact">Deactivated</button>
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
        <?php include("../includes/alert.php"); ?>
        <?php 
        if($result->num_rows!=0){
            while($row = mysqli_fetch_array($result)){
                echo "
                    <table class=\"table text-center \">
                        <tr class=\"details row d-grid\">
                            <td class=\"col d-flex d-flex align-items-center\">
                                <div class=\"col d-grid align-items-center\">
                                    <img class=\"img-thumbnail image-admin-css\" src=\"{$row['pfp_path']}\" style=\"object-fit: cover;\">
                                </div>
                                <div class=\"col d-grid align-items-center text-wrap\">{$row['email']}</div>
                                <div class=\"col d-grid align-items-venter text-wrap\">{$row['description']}</div>
                            </td>
                            <div class=\"col d-grid align-items-start text-wrap justify-content-center fw-bold\">{$row['lname']}, {$row['fname']}</div>
                            <td class=\"col d-flex align-items-center\">
                                <div class=\"col d-grid align-items-center text-wrap\">{$row['user_id']}</div>
                                <div class=\"col d-grid align-items-center text-wrap\">{$row['addressline']}</div>
                                <div class=\"col d-grid align-items-center text-wrap\">{$row['phone']}</div>
                                <div class=\"col d-grid align-items-center\">
                                    <div class=\"row d-grid gap-1\">
                                        <form action=\"edit.php\" method=\"post\">
                                            <button class=\"btn btn-warning btn-sm w-100\" name=\"update_id\" value=\"{$row['user_id']}\">EDIT</button>
                                        </form>
                                        <div class=\"dropdown d-block\">
                                            <button class=\"btn btn-danger btn-sm w-100 dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                                DELETE
                                            </button>
                                            <ul class=\"dropdown-menu\">
                                                <form action=\"delete.php\" method=\"post\">
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"delete_id\" value=\"{$row['user_id']}\">YES</button>
                                                </form>
                                                <form action=\"\" method=\"post\">
                                                    <button class=\"dropdown-item btn-sm w-100\" name=\"no\" value=\"{$row['user_id']}\">NO</button>
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
        }else{
            echo "<p class=\"text-center mt-2 fw-bold\">No users found.</p>";
        }
        ?>
        </div>
    </div>
</body>
</html>
<?php
    include('../includes/footer.php');
?>