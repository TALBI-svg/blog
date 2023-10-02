<?php
include_once 'admin_components/headers_admin.php';

if(isset($_SESSION['admin_id'])){
    if(isset($_POST['createUserBtn'])){
        $form_errors=array();
        $required_fields=array('username','email','firstname','lastname','password');
        $form_errors=array_merge($form_errors,check_empty_fields($required_fields));
        $fields_to_check_length=array('username'=>4,'firstname'=>4,'lastname'=>4,'password'=>8);
        $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));
        $form_errors=array_merge($form_errors,check_email($_POST));
    
        $username=$_POST['username'];
        $email=$_POST['email'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $password=$_POST['password'];
    
        if(checkDuplicateField('users','username',$username, $connection)){
            $result=falshMessage('username already taken try an other one!');
        }
        elseif(checkDuplicateField('users','email',$email, $connection)){
            $result=falshMessage('email already taken taken try an other one!');
        }
        elseif(empty($form_errors)){
            $hashed_password=password_hash($password, PASSWORD_DEFAULT);
            try{
                $sql="INSERT INTO users (username,email,firstname,lastname,password,created_at) 
                      VALUES (:username,:email,:firstname,:lastname,:password,now() )";
                $statement=$connection->prepare($sql);
                $statement->execute(array(':username'=>$username,':email'=>$email,':firstname'=>$firstname,':lastname'=>$lastname,':password'=>$hashed_password));
                if($statement->rowCount()==1){
                    $result=falshMessage('User created successfully','Pass');

                    $origin_password=$hashed_password;
                    $password_owner=$username;
                    addDefaultPassword($connection,$origin_password,$password_owner);
                }
            }catch(PDOException $ex){
                $result=falshMessage('Error Exception '.$ex->getMessage());
            }
        }else{
            if(count($form_errors) == 1){
                $result=falshMessage('One error in the form');
            }else{
                $result=falshMessage('There were '.count($form_errors).' errors in the form');
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
    <div class="container p-3 px-4 CREATEUSER">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <form action="" method="post" autocomplete="off">
                  <div class="row title-area mb-2">
                      <h3>Create user</h3>
                  </div>
                  <div class="mb-3">
                    <input type="text" name="username" class="form-control" value="" placeholder="Username">
                  </div>
                  <div class="mb-3">
                    <input type="text" name="email" class="form-control" value="" placeholder="Email address">
                  </div>
                  <div class="mb-3">
                    <input type="text" name="firstname" class="form-control" value="" placeholder="Firstname">
                  </div>
                  <div class="mb-3">
                    <input type="text" name="lastname" class="form-control" value="" placeholder="Lastname">
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control" value="" placeholder="Password">
                  </div>
                  <button type="submit" name="createUserBtn" class="signupBtn btn btn-warning">Create user</button>
                </form>
                <?php if(isset($result)) echo $result; ?>
                <?php 
                if(isset($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
        </div>
    </div>
</body>
</html>