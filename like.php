<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){            
    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $post_id=$_GET['post_id'];
        $id=$_SESSION['id']; 
    
        // Get "$username" from db
        $sql="SELECT * FROM users WHERE id = :id";
        $statement=$connection->prepare($sql);
        $statement->execute(array(':id'=>$id));
        while($res= $statement->fetch()){
            $like_owner=$res['username'];
            $like_owner_img=$res['profile_pic'];
            $like_post=1;


            $sql="SELECT COUNT(*) AS CountLikes FROM likes WHERE like_owner='$like_owner' AND post_liked_id='$post_id'";
            $statement=$connection->query($sql);
            if(!$statement){
                die('invalid query!'.$connection->getMessage());
            } 
            while($res=$statement->fetch()){
                $like=$res['CountLikes'];
                if($like>=1){
                    $sql="DELETE  FROM likes WHERE like_owner='$like_owner' AND post_liked_id='$post_id'";
                    $statement=$connection->query($sql);
                    header("location:post_details.php?post_id=$post_id");
                }else{
                    try {
                        $sql="INSERT INTO likes (like_post, like_owner, like_owner_img, post_liked_id)
                              VALUES (:like_post, :like_owner, :like_owner_img, :post_liked_id )";
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':like_post'=>$like_post, ':like_owner'=>$like_owner, ':like_owner_img'=>$like_owner_img, ':post_liked_id'=>$post_id ));
                        if($statement->rowCount()==1){
                            $user_post=getUserPost($connection,$post_id);
                            $post_title=getPostTitle($connection,$post_id);
                            $event="like";
                            $content="your post";
                            $noti_sender=$like_owner;
                            $noti_sender_img=$like_owner_img;
                            $noti_receiver=$user_post;
                            NotiFromUser($connection,$event,$content,$noti_sender,$noti_sender_img,$noti_receiver,$post_id,$post_title);                        
                        }
                    } catch (PDOException $ex) {
                        echo "Error Exception ".$ex->getMessage();
                    }
                }
            }
        }        
    }
    
}else{
    redirect("login");
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


            