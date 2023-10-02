<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';


if($_SESSION['id']){
    $id=$_SESSION['id'];
    $username=getUsername($connection,$id); 

    function getComment_owner($conn,$comment_id,$us_name){
        $sql="SELECT * FROM comments WHERE id=$comment_id AND comment_owner='$us_name'";
        $statement=$conn->query($sql);
        if(!$statement){
            die('invalid query!').$conn->getMessage();
        }
        while($res=$statement->fetch()){
            $comment_owner=$res['comment_owner'];
            return $comment_owner;
        }
    }

    function goBack($conn,$comment_id){
        $sql="SELECT post_commented_id FROM comments WHERE id='$comment_id'";
        $statement=$conn->query($sql);
        if(!$statement){
            die("invalid query!").$conn->getMessage();
        }
        while($res=$statement->fetch()){
            $post_id=$res['post_commented_id'];
            header("location:post_details.php?post_id=$post_id");
        }
    }

    $comment=strip_tags($_POST['comment']);
    $comment_id_hidden=$_POST['comment_id_hidden'];
    $comment_owner=getComment_owner($connection,$comment_id_hidden,$username);

    if($comment_owner===$username){
        if(!empty($_POST['comment'])){
            // update comment
            $sql="UPDATE comments SET comment_text=:comment_text, comment_time=now() WHERE comment_owner=:username AND id=:comment_id";
            $statement=$connection->prepare($sql);
            $statement->execute(array(':comment_text'=>$comment, ':username'=>$username, ':comment_id'=>$comment_id_hidden));
            if($statement->rowCount()==1){
                
                goBack($connection,$comment_id_hidden);  
            }
        }else{

            goBack($connection,$comment_id_hidden);
        }
    }else{
            goBack($connection,$comment_id_hidden);

    }
}


?>