<?php
include_once 'admin_components/headers_admin.php';


if(isset($_SESSION['admin_id'])){
    $id=$_SESSION['admin_id'];
    $username_admin=getAdminUsername($connection,$id);

    if($_SERVER['REQUEST_METHOD']==='GET'){
        $post_id=$_GET['post_id'];

        if($post_id!=null){
            // check if user_id exist in database?
            $sql="SELECT COUNT(*) AS isPostIdExist FROM posts WHERE id='$post_id'";
            $statement=$connection->query($sql);
            if(!$statement){
                die("invalid query!").$connection->getMessage();
            }
            while($res=$statement->fetch()){
                $isPostIdExist=$res['isPostIdExist'];
                if($isPostIdExist>0){
                    // echo "ok";
                    $sql="DELETE FROM posts WHERE id='$post_id'";
                    $statement=$connection->query($sql);
                    header("location: panel_options.php");

                }else{
                    echo "Post_id Invalid plz try again!";
                }
            }
        }else{
            echo "Post_id invalid or Null plz try again!";
        }
    }
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