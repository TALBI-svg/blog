<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';



if(isset($_POST['loginBtn'])){
    $form_errors=array();
    $required_fields=array('username','password');
    $form_errors=array_merge($form_errors,check_empty_fields($required_fields));


    if(empty($form_errors)){
        $user=$_POST['username'];
        $password=$_POST['password'];

        $admin=strtolower($user);
        if(str_contains($admin, "admin")){
            // echo "redirect to dashboard";
            isset($_POST['remember']) ? $remember = $_POST['remember'] : $remember="";
            $sql='SELECT * FROM admins WHERE username_admin = :username_admin';
            $statement=$connection->prepare($sql);
            $statement->execute(array(':username_admin' => $user));
            while($row=$statement->fetch()){
                $admin_id=$row['id'];
                $hashed_password=$row['password'];
                $username_admin=$row['username_admin'];

                if(password_verify($password, $hashed_password)){
                    $_SESSION['admin_id']=$admin_id;
                    $_SESSION['username_admin']=$username_admin;

                    if ($remeber == "yes"){
                        rememberMe($id);
                    }
                    header("location:admin/dashboard.php");
                }else{
                    $result=falshMessage('Invalid password or username_admin');
                }
            }

        }else{
            isset($_POST['remember']) ? $remember = $_POST['remember'] : $remember="";
            // check if user exists in database 
            $sql='SELECT * FROM users WHERE username = :username';
            $statement=$connection->prepare($sql);
            $statement->execute(array(':username' => $user));
            while($row=$statement->fetch()){
                $id=$row['id'];
                $hashed_password=$row['password'];
                $username=$row['username'];
                $status=$row['status'];

                if(password_verify($password, $hashed_password)){
                    if($status==='disactive'){
                        $url="<a href='contact.php'>Contact</a>";
                        $result=falshMessage('You can\'t login your account hase been blocked contact support ! '.$url);
                    }else{

                        $_SESSION['id']=$id;
                        $_SESSION['username']=$username;
                        
                        if ($remeber == "yes"){
                            rememberMe($id);
                        }
                        redirect('index');
                    }
                }else{
                    $result=falshMessage('Invalid password or username');
                }
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
        <div class="row d-flex justify-content-center align-items-center" style="height:80vh;">
            <div class="col-12 col-md-4 col-lg-3 mt-5 bg-light py-3 rounded shadow-sm p-3 mb-5">
                <form action="" method="post" autocomplete="off">
                  <h2 class="mb-3 text text-warning">Login</h2>
                  <div class="mb-3">
                    <input type="text" name="username" class="form-control" value="" placeholder="Username">
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control" value="" placeholder="Password">
                  </div>
                  <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="remember" value="yes" for="exampleCheck1">remember me</label>
                  </div>
                  <div class="row">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" name="loginBtn" class="signupBtn btn btn-warning">Login</button>
                    </div>
                  </div>
                </form>
                <?php if(isset($result)) echo $result;?>
                <?php if(isset($form_errors)) echo show_errors($form_errors);?>
            </div>
        </div>
    </div>
    
</body>
</html>