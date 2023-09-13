<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

// if(isset($_SESSION['id'])){
//     $id=$_SESSION['id'];

// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if(isset($_SESSION['id'])){
        $id=$_SESSION['id'];

        if($_SERVER['REQUEST_METHOD'] == "GET"){
            $post_id=$_GET['post_id'];
            $sql="DELETE FROM posts WHERE id=$post_id";
            $connection->query($sql);
            redirect("my_posts");
        }

    }else{
        redirect("login");
    }

    ?>
    
</body>
</html>