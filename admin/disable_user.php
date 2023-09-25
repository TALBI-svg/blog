<?php 
include_once 'admin_components/headers_admin.php';
include_once '../resources/session.php';
include_once '../resources/db.php';
include_once '../resources/utilities.php';

if(isset($_SESSION['admin_id'])){
    if($_SERVER['REQUEST_METHOD']==='GET'){
        $user_id=$_GET['user_id'];

        $sql="SELECT status FROM users WHERE id='$user_id'";
        $statement=$connection->query($sql);
        if(!$statement){
            die('invalid query!').$connection->getMessage();
        }
        while($res=$statement->fetch()){
            $status=$res['status'];
            if($status==='disactive'){
                // echo "ok";
                // header("location:dashboard.php");
                try {
                    $status='active';
                    
                    $sql="UPDATE users SET status =:status WHERE id =:user_id";
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':status'=>$status, ':user_id'=>$user_id));
        
                    if($statement->rowCount()==1){
                        header("location:panel_options.php");
                    }
                } catch(PDOException $ex){
                    die('Error Exception ').$ex->getMessage();
                }

            }else{
                // echo "err";
                try {
                    $status='disactive';

                    $sql="UPDATE users SET status =:status WHERE id =:user_id";
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':status'=>$status, ':user_id'=>$user_id));
        
                    if($statement->rowCount()==1){
                        header("location:panel_options.php");           
                    }
                } catch(PDOException $ex){
                    die('Error Exception ').$ex->getMessage();
                }
            }
        }

    }
}else{
    header('location:../login.php');
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