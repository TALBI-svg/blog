<?php 
include_once 'admin_components/headers_admin.php';
include_once '../resources/session.php';
include_once '../resources/db.php';
include_once '../resources/utilities.php';

if($_SESSION['admin_id']){
    if($_SERVER['REQUEST_METHOD']==='GET'){
        $user_id=$_GET['user_id'];
        $username=getUsername($connection,$user_id);

        function getOriginPassword($conn,$username){
            $sql="SELECT * FROM defaultpassword WHERE password_owner=:username";
            $statement=$conn->prepare($sql);
            $statement->execute(array(':username'=>$username));
            while($res= $statement->fetch()){
              $origin_password=$res['origin_password'];
              return $origin_password;
            }
        }
        $origin_password=getOriginPassword($connection,$username);
        
        $sql="SELECT * FROM users WHERE username='$username'";
        $statement=$connection->query($sql);
        if(!$statement){
            die('invalid query!').$connection->getMessage();
        }
        while($res=$statement->fetch()){
            $pass=$res['password'];
            if($pass===$origin_password){
                // echo "ok";
                header("location: dashboard.php");
            }else{
                // echo "err";
                try{
                    // $origin_password=getOriginPassword($connection,$username);
                    $sql="UPDATE users SET password =:password WHERE username =:username";
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':password'=>$origin_password, ':username'=>$username));
                    
                    if($statement->rowCount()==1){
                        header("location: dashboard.php");
                    }
                }catch(PDOException $ex){
                    $result=falshMessage('Error Exception '.$ex->getMessage());
                }
            }
        }

        
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