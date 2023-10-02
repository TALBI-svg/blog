<?php

function check_empty_fields($required_fields){
    $form_errors=[];

    foreach($required_fields as $required_field){
        if(!isset($_POST[$required_field]) or $_POST[$required_field] ==null){
            $form_errors[]=$required_field.' is required !';
        }
    }
    return $form_errors;
}

function check_min_length($fields_to_check_length){
    $form_errors=[];
    foreach($fields_to_check_length as $field_name=>$minimum_length_required){
        if(strlen(trim($_POST[$field_name])) <$minimum_length_required){
            $form_errors[]=$field_name.' is too short, must be '.$minimum_length_required.' characters long';
        }
    }
    return $form_errors;
}

function check_email($data){
    $form_errors=[];
    $key='email';
    if(array_key_exists($key,$data)){
        if($_POST[$key]!=null){
            $key=filter_var($key,FILTER_SANITIZE_EMAIL);
            if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                $form_errors[]=$key.' email addres not valid!';
            }
        }
    }
    return $form_errors;
}

function show_errors($form_errors_array){
    $errors='<p><ul class="ist-unstyled ps-0">';
    foreach($form_errors_array as $error){
        $errors.='<li class="list-unstyled text text-danger">'.ucfirst($error) .'</li>';
    }
    $errors.='</ul></p>';
    return $errors;
}

function falshMessage($message, $passOrFail=''){
    if($passOrFail == 'Pass'){
        $data='<div class="alert alert-success border border-0 mt-3 shadow-sm">'.$message.'</p>';
    }else{
        $data='<div class="alert alert-danger border border-0 mt-3 shadow-sm">'.$message.'</p>';
    }
    return $data;
}

function redirect($page){
    header("Location: {$page}.php");
}

function checkDuplicateField($table, $column_name, $value, $connection){
    try {
        $sql='SELECT * FROM '.$table.' WHERE '.$column_name.' = :'.$column_name.'';
        $statement=$connection->prepare($sql);
        $statement->execute(array(':'.$column_name.''=>$value));

        if($row= $statement->fetch()){
            return true;
        }
        return false;
    } catch (PDOExecption $ex) {
        //handle exception
        $result=falshMessage('Exception Error'.$ex->getMessage());
    }
}

function rememberMe($userId){
    $encryptCookieData=base64_encode("6dchaui0091pjutv3jhomscvv4{$userId}");
    setcookie("rememberUserCookie", $encryptCookieData, time()+60*60*24*1, "/");
}

function isCookieValid($connection){
    $isValid=false;
    // decode cookie and extrcat ID
    if(isset($_COOKIE['rememberUserCookie'])){
        $decryptCookieData=base64_decode($_COOKIE['rememberUserCookie']);
        $userId=explode("6dchaui0091pjutv3jhomscvv4", $decryptCookieData);
        $userID=$userId[1];

        // check if id retrieved from the cookie exists in the db
        $sql="SELECT * FROM users WHERE id = :id";
        $statement=$connection->prepare($sql);
        $statement->execute(array(':id'=>$userID));

        if($row=$statement->ftech()){
            $id=$row['id'];
            $username=$row['username'];

            $_SESSION['id']=$id;
            $_SESSION['username']=$username;

            $isValid=true;
        }else{
            $isValid=false;
            $this->signout();
        }
    }
    return $isValid;
}

function signout(){
    isset($_SESSION['username']);
    isset($_SESSION['id']);

    if($_COOKIE['rememberUserCookie']){
        unset($_COOKIE['rememberUserCookie']);
        setcookie('rememberUserCookie', -1, '/');
    }

    session_destroy();
    session_regenerate_id(true);
    redirect('index');
}

function isValidImage($file){
    $form_errors=[];
    $part=explode(".", $file);
    $extension=end($part);
    switch(strtolower($extension)){
        case 'jpg':
        case 'gif':
        case 'bmp':
        case 'png':
        case 'webp':
        return $form_errors;
    }
    $form_errors[]=$extension." is not valid image extension";
    return $form_errors;
}

function uploadAvatar($username,$connection,$user_id){
    $isImageMoved=false;
    if($_FILES['avatar']['tmp_name']){
        $temp_file=$_FILES['avatar']['tmp_name'];
        $directory_separator=DIRECTORY_SEPARATOR; //uploads/
        $avatar_name=$username.".jpg";
        $path="assets/images".$directory_separator.$avatar_name; //uploads/demo.jpg
        if(move_uploaded_file($temp_file, $path)){
            try{
                $sql="UPDATE users SET profile_pic=:profile_pic WHERE id=:user_id";
                $statement=$connection->prepare($sql);
                $statement->execute(array(':profile_pic'=>$avatar_name, ':user_id'=>$user_id));

                if($statement->rowCount()==1){
                  $result=falshMessage('Profile added successfully','Pass');
                }
            }catch(PDOException $ex){
                $result=falshMessage('Error Exception '.$ex->getMessage());
            }
            $isImageMoved=true;
        }
    }
    return $isImageMoved;
}

function getUsername($conn,$id){
    $sql="SELECT * FROM users WHERE id = :id";
    $statement=$conn->prepare($sql);
    $statement->execute(array(':id'=>$id));
    while($res= $statement->fetch()){
      $username=$res['username'];
      return $username;
    }
}

function getAdminUsername($conn,$id){
    $sql="SELECT * FROM admins WHERE id = :id";
    $statement=$conn->prepare($sql);
    $statement->execute(array(':id'=>$id));
    while($res= $statement->fetch()){
      $username_admin=$res['username_admin'];
      return $username_admin;
    }
}


function NotiFromUser($conn,$event,$content,$noti_sender,$noti_sender_img,$noti_receiver,$post_id,$post_title){
    $contents=$noti_sender.' '.$event.' '.$content;
    // check if notification is already exist or not
    $sql="SELECT COUNT(*) AS isNotiExist FROM notifications WHERE content='$contents' AND noti_sender='$noti_sender' AND noti_receiver='$noti_receiver' AND post_reacted_id='$post_id'";
    $statement=$conn->query($sql);
    if(!$statement){
        die("invalid query for notification!").$conn->getMessage();
    }
    while($res=$statement->fetch()){
        $isNotiExist=$res['isNotiExist'];
        if($isNotiExist>=1){
            header("location:post_details.php?post_id=$post_id");
        }else{
            if($noti_sender===$noti_receiver){
                // echo "noti_sender and noti_receiver are the same!";
                header("location:post_details.php?post_id=$post_id");
            }else{
                // echo "not the same";
                try {
                    
                    $contents=$noti_sender.' '.$event.' '.$content;
                    $sql="INSERT INTO notifications (content, date_noti, noti_sender, noti_sender_img, noti_receiver, post_reacted_id, post_reacted_title)
                          VALUES (:content, now(), :noti_sender, :noti_sender_img, :noti_receiver, :post_reacted_id, :post_reacted_title)";
                    $statement=$conn->prepare($sql);
                    $statement->execute(array(':content'=>$contents, ':noti_sender'=>$noti_sender, ':noti_sender_img'=>$noti_sender_img, ':noti_receiver'=>$noti_receiver, ':post_reacted_id'=>$post_id, ':post_reacted_title'=>$post_title));
                    if($statement->rowCount()==1){
                        header("location:post_details.php?post_id=$post_id");
                    }
                } catch (PDOException $ex) {
                    echo "Error Exception ".$ex->getMessage();
                }
            }
        }
    }
}

function getUserPost($conn,$post_id){
    $sql="SELECT * FROM posts WHERE id=$post_id";
    $statement=$conn->query($sql);
    if(!$statement){
        die("invalide query".$conn->error);
    }
    while($res=$statement->fetch()){
        $user_post=$res['user_post'];
        return $user_post;
    }
}

function getPostTitle($conn,$post_id){
    $sql="SELECT * FROM posts WHERE id=$post_id";
    $statement=$conn->query($sql);
    if(!$statement){
        die("invalide query".$conn->error);
    }
    while($res=$statement->fetch()){
        $title=$res['title'];
        return $title;
    }
}

function addDefaultPassword($conn, $origin_password, $password_owner){
    try {
        $sql="INSERT INTO defaultPassword (origin_password, password_owner)
              VALUES (:origin_password, :password_owner) ";
        $statement=$conn->prepare($sql);
        $statement->execute(array(':origin_password'=>$origin_password, ':password_owner'=>$password_owner));
    }catch(PDOException $ex){
        $result=falshMessage('Error Exception '.$ex->getMessage());
    }
}

function setDefaultImage($uri,$default){
    if($uri !=null){
        $origine_image="assets/images/$uri";
        $image=$origine_image;
        return $image;
    }else{
        $image=$default;
        return $image;
    }
}

function CountLikes($conn,$post_id){
    $sql="SELECT COUNT(like_post) as likes_nbr FROM likes WHERE post_liked_id='$post_id'";
    $statement=$conn->query($sql);
    if(!$statement){
        die("invalid query for count likkes".$conn->getMessage());
    }
    while($res=$statement->fetch()){
        $likes_nbr=$res['likes_nbr'];
        return $likes_nbr;

    }
}
?>

