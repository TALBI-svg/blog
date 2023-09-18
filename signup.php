<?php
include_once 'components/headers.php';
include_once 'resources/utilities.php';


if(isset($_POST['signupBtn'])){
    $form_errors=array();
    $required_fields=array('username','email','firstname','lastname','password');

    // foreach($required_fields as $required_field){
    //     if(!isset($_POST[$required_field]) or $_POST[$required_field] ==null){
    //         $form_errors[]=$required_field.' is required !';
    //     }
    // }

    $form_errors=array_merge($form_errors,check_empty_fields($required_fields));

    $fields_to_check_length=array('username'=>4,'firstname'=>4,'lastname'=>4,'password'=>8);
    $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));

    $form_errors=array_merge($form_errors,check_email($_POST));

    $username=$_POST['username'];
    $email=$_POST['email'];
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $password=$_POST['password'];

    // if(checkDuplicateUsername($username, $connection)){
    //     $result=falshMessage('username already taken!');
    // }
    if(checkDuplicateField('users','username',$username, $connection)){
        $result=falshMessage('username already taken try an other one!');
    }
    elseif(checkDuplicateField('users','email',$email, $connection)){
        $result=falshMessage('email already taken taken try an other one!');
    }

    elseif(checkDuplicateField('admins','username_admin',$username, $connection)){
      $result=falshMessage('Username_admin already taken try an other one!');
    }
    elseif(checkDuplicateField('admins','email',$email, $connection)){
        $result=falshMessage('Email_admin already taken taken try an other one!');
    }
    elseif(empty($form_errors)){

        $hashed_password=password_hash($password, PASSWORD_DEFAULT);

        $admin=strtolower($email);
        if(str_contains($admin, "admin")){
          // echo "admin";
          try{
              $sql="INSERT INTO admins (username_admin,email,firstname,lastname,password,created_at) 
                    VALUES (:username_admin,:email,:firstname,:lastname,:password,now() )";
              $username_admin=$username.'_'.'admin';
              $statement=$connection->prepare($sql);
              $statement->execute(array(':username_admin'=>$username_admin,':email'=>$email,':firstname'=>$firstname,':lastname'=>$lastname,':password'=>$hashed_password));
              if($statement->rowCount()==1){
                  $result=falshMessage('Admin register with success your username is '.'<span class="fw-bold">'.$username_admin.'<span>','Pass');
              }
          }catch(PDOException $ex){
              $result=falshMessage('Error Exception '.$ex->getMessage());
          }

        }else{

          // echo "user";
          try{
              // if(isset($_POST['username'])){
              $sql="INSERT INTO users (username,email,firstname,lastname,password,created_at) 
                    VALUES (:username,:email,:firstname,:lastname,:password,now() )";
              $statement=$connection->prepare($sql);
              $statement->execute(array(':username'=>$username,':email'=>$email,':firstname'=>$firstname,':lastname'=>$lastname,':password'=>$hashed_password));
              if($statement->rowCount()==1){
                  // ini_set(header('location: login.php'), 10);
                  $result=falshMessage('Register success','Pass');
                  
                  $origin_password=$hashed_password;
                  $password_owner=$username;
                  addDefaultPassword($connection,$origin_password,$password_owner);
              }
              // }
          }catch(PDOException $ex){
              $result=falshMessage('Error Exception '.$ex->getMessage());
          }
        }
    }else{
        if(count($form_errors) == 1){
            $result=falshMessage('One error in the form');
           
        }else{
            $result=falshMessage('There were '.count($form_errors).' errors in the form');
            
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
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style="height:80vh;">
            <div class="col-12 col-md-4 col-lg-3 mt-5 bg-light py-3 rounded shadow-sm p-3 mb-5">
                <form action="" method="post">
                  <h2 class="mb-3 text text-warning">Register</h2>
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
                  <!-- <div class="mb-3">
                    <input type="password" name="c_password" class="form-control" <?php  ?> placeholder="confirm password">
                  </div> -->
                  <!-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="conditions" for="exampleCheck1">Accept condition of privacy policy</label>
                  </div> -->
                  <button type="submit" name="signupBtn" class="signupBtn btn btn-warning">Signup</button>
                </form>
                <?php 
                    if(isset($result)) echo $result;
                 ?>
                <?php if(isset($form_errors)) echo show_errors($form_errors);?>
            </div>
        </div>
    </div>
</body>
</html>