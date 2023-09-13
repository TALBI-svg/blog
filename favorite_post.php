<?php
include_once 'components/headers.php';
include_once 'resources/db.php';
include_once 'resources/session.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    // Get "$username" from db
    $favorites_owner=getUsername($connection,$id);

    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $post_id=$_GET['post_id'];

        $sql="SELECT * FROM posts WHERE id=$post_id";
        $statement=$connection->query($sql);
        if(!$statement){
            die("invalid query!".$connection->getMessage());
        }
        while($res=$statement->fetch()){
            $post_creator=$res['user_post'];
            $post_title=$res['title'];
            $post_descripe=$res['description'];
            $post_img=$res['image'];
            $post_status=1;
        }

        // check if post already favorited?
        $sql="SELECT COUNT(*) AS isFavorited FROM favorites WHERE post_creator='$post_creator' and favorit_post_id='$post_id' and favorites_owner='$favorites_owner'";
        $statement=$connection->query($sql);
        if(!$statement){
            die('invalid query!').$connection->getMessage();
        }
        while($res=$statement->fetch()){
            $check=$res['isFavorited'];
            if($check!=1){
                try {
                    $sql="INSERT INTO favorites (status, fave_date, post_title, post_descripe, post_img, post_creator, favorit_post_id, favorites_owner)
                          VALUES (:status, now(), :post_title, :post_descripe, :post_img, :post_creator, :favorit_post_id, :favorites_owner)";
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':status'=>$post_status,':post_title'=>$post_title, ':post_descripe'=>$post_descripe, ':post_img'=>$post_img, ':post_creator'=>$post_creator, ':favorit_post_id'=>$post_id, ':favorites_owner'=>$favorites_owner));
                    if($statement->rowCount()==1){
                        header("location:favorite_post_view.php");
                    }
                } catch (PDOException $ex) {
                    echo "Error Exception ".$ex->getMessage();
                }
            }else{
                header("location:favorite_post_view.php");
                // echo "is already favorited";
            }
        
        }
    }
}else{
    redirect("login");
}

?>