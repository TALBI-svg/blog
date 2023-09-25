<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){
    if(isset($_POST['resetBtn'])){
        $form_errors=array();

        $required_fields=array('email','new_password','confirm_password');
        $form_errors=array_merge($form_errors,check_empty_fields($required_fields));

        $fields_to_check_length=array('new_password'=>8);
        $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));

        $form_errors=array_merge($form_errors,check_email($_POST));

        if(empty($form_errors)){
            // collect data
            $email=$_POST['email'];
            $new_password=$_POST['new_password'];
            $confirm_password=$_POST['confirm_password'];


            if($new_password != $confirm_password){
                $result=falshMessage('New password and confirm password are not match!');
            }else{
                try{
                    $sql='SELECT email FROM users WHERE email = :email';
                    $statement=$connection->prepare($sql);
                    $statement->execute(array(':email'=>$email));

                    // if email exists in db
                    if($statement->rowCount()== 1){
                        $hashed_password=password_hash($new_password,PASSWORD_DEFAULT);

                        $sql='UPDATE users SET password= :password WHERE email = :email';
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':password'=>$hashed_password,':email'=>$email));

                        $result=falshMessage('Password Reseted Successfully','Pass');
                    }else{
                        $result=falshMessage('The address email provided dose\'t exists! try again');
                    }
                }catch(PDOException $ex){
                    $result=falshMessage('Error Exception'.$ex->getMessage());
                }
            }

        }else{
            if(count($form_errors) ==1){
                $result=falshMessage('One error in the form');
            }else{
                $result=falshMessage('There were '.count($form_errors).' errors in the form');
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
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <form action="" method="post" autocomplete="off">
                    <div class="row title-area mt-3 mb-2">
                        <h3>Reset password</h3>
                    </div>
                    <div class="mb-3">
                      <input type="text" name="email" class="form-control" value="" placeholder="Email">
                    </div>
                    <div class="mb-3">
                      <input type="password" name="new_password" class="form-control" value="" placeholder="New Password">
                    </div>
                    <div class="mb-3">
                      <input type="password" name="confirm_password" class="form-control" value="" placeholder="Confirm Password">
                    </div>
                    <button type="submit" name="resetBtn" class="signupBtn btn btn-warning">Reset Password</button>
                    </form>
                    <?php if(isset($result)) echo $result;?>
                    <?php if(isset($form_errors)) echo show_errors($form_errors);?>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>