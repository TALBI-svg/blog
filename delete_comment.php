<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $username=getUsername($connection,$id);

    if($_SERVER['REQUEST_METHOD']==='GET'){
        $comment_id=$_GET['comment_id'];

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
                return $post_id;
            }
        }

        $comment_owner=getComment_owner($connection,$comment_id,$username);
        $post_id=goBack($connection,$comment_id);
        if($comment_owner===$username){
            $sql="DELETE FROM comments WHERE id='$comment_id' AND comment_owner='$comment_owner'";
            $statement=$connection->query($sql);
            
            // echo "deleted!";
            header("location:post_details.php?post_id=$post_id");
        }else{
            
            // echo "is not logged!";
            header("location:post_details.php?post_id=$post_id");
        }
    }
    
}else{
    redirect('login');
}

?>
