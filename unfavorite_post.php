<?php
include_once 'components/headers.php';
include_once 'resources/db.php';
include_once 'resources/session.php';


if(isset($_SESSION['id'])){
    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $id=$_SESSION['id'];
        // Get "$username" from db
        $favorites_owner=getUsername($connection,$id);
        
        $post_id=$_GET['post_id'];

        $sql="SELECT * FROM posts WHERE id=$post_id";
        $statement=$connection->query($sql);
        if(!$statement){
            die("invalid query!".$connection->getMessage());
        }
        while($res=$statement->fetch()){
            $post_creator=$res['user_post'];
        }

        $sql="SELECT COUNT(*) AS isStatusTrue FROM favorites WHERE post_creator='$post_creator' and favorit_post_id='$post_id' and favorites_owner='$favorites_owner'";
        $statement=$connection->query($sql);
        if(!$statement){
            die('invalid query!'.$connection->getMessage());
        }
        while($res=$statement->fetch()){
            $status=$res['isStatusTrue'];
            if($status>=1){
                $sql="DELETE  FROM favorites WHERE post_creator='$post_creator' AND favorit_post_id='$post_id' and favorites_owner='$favorites_owner'";
                $statement=$connection->query($sql);
                header("location:favorite_post_view.php");
            }
        }
    }
}else{
    redirect("login");
}

?>