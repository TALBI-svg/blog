<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';


if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $username=getUsername($connection,$id);

    if($_SERVER['REQUEST_METHOD']==='GET'){
        $noti_id=$_GET['noti_id'];
        $post_id=$_GET['post_id'];
        // echo $noti_id.' '.$post_id;
        $sql="SELECT COUNT(*) AS isReadedNoti FROM notifications WHERE id='$noti_id' and readed='false'";
        $statement=$connection->query($sql);
        if(!$statement){
            die('invalid query!').$connection->getMessage();
        }
        while($res=$statement->fetch()){
            // check if notification is already readed ot not
            $isReadedNoti=$res['isReadedNoti'];
            if($isReadedNoti==1){
                try {
                    $readed='true';
                    $sql="UPDATE notifications SET readed=:readed, date_noti=now() WHERE id=:noti_id";
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':readed'=>$readed,':noti_id'=>$noti_id));
                    if($statement->rowCount()==1){
                        header("location:post_details.php?post_id=$post_id");
                    }
                } catch (PDOException $ex) {
                    echo "Error Exception ".$ex->getMessage();
                }
            }else{
                header("location:post_details.php?post_id=$post_id");
            }
        }
    }

}else{
    redirect('login');
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