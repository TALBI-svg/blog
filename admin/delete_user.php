<?php
include_once 'admin_components/headers_admin.php';
include_once '../resources/session.php';
include_once '../resources/db.php';
include_once '../resources/utilities.php';

if(isset($_SESSION['admin_id'])){
    if($_SERVER['REQUEST_METHOD']==='GET'){
        $user_id=$_GET['user_id'];
        $sql="DELETE FROM users WHERE id='$user_id'";
        $statement=$connection->query($sql);
        header("location: panel_options.php");
    }
}else{
    header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>