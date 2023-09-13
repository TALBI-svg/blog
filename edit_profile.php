<?php 
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/utilities.php';
include_once 'resources/db.php';


if((isset($_SESSION['id']) || isset($_GET['user_indetity'])) && !isset($_POST['updateBtn'])){
    if(isset($_GET['user_indetity'])){
        $url_encoded_id=$_GET['user_identity'];
        $decoded_id=base64_decode($url_encoded_id);
        $user_id_array=explode("encodeuserid", $decoded_id);
        $id=$user_id_array[1];

    }else{
        $id=$_SESSION['id'];
    }
    $sql="SELECT * FROM users WHERE id = :id";
    $statement=$connection->prepare($sql);
    $statement->execute(array(":id"=>$id));

    while($res=$statement->fetch()){
        $username=$res['username'];
        $email=$res['email'];
        $firstname=$res['firstname'];
        $lastname=$res['lastname'];
        $title=$res['title'];
        $address=$res['address'];
    }
    $encode_id=base64_encode("encodeuserid{$id}");
    
}else if(isset($_POST['updateBtn'])){
    $form_errors=[];

    $required_fileds=array('email','firstname','lastname','title','address');
    $form_errors=array_merge($form_errors, check_empty_fields($required_fileds));

    $fields_to_check_length=array('firstname'=>4,'lastname'=>4,'title'=>5,'address'=>5);
    $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));

    $form_errors=array_merge($form_errors,check_email($_POST));

    // check if image valid 
    isset($_FILES['avatar']['name']) ? $avatar=$_FILES['avatar']['name'] : $avatar=null;
    if($avatar !=null){
        $form_errors=array_merge($form_errors, isValidImage($avatar));
        // echo isValidImage($avatar);
    }

    $email=$_POST['email'];
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $title=$_POST['title'];
    $address=$_POST['address'];
    $hidden_id=$_POST['hidden_id'];
    
    if(empty($form_errors)){
        try{
            $sql="UPDATE users SET firstname =:firstname, lastname =:lastname, title=:title, address=:address, email =:email WHERE id =:id";
            $statement=$connection->prepare($sql);
            $statement->execute(array(':firstname'=>$firstname, 'lastname'=>$lastname, ':title'=>$title, 'address'=>$address, 'email'=>$email, ':id'=>$hidden_id));

            if($statement->rowCount()==1 || uploadAvatar($username,$connection,$id)){
                $result=falshMessage('Profile updated successfully','Pass');
            }else{
                $result=falshMessage('Profile are not updated!');
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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style_profile.css">
</head>
<body>
    <div class="container EditProfile">
        <div class="row  d-flex justify-content-between">
            <?php if(isset($_SESSION['username'])):?>
            <div class="col-12 col-md-4 col-lg-3">
              <div class="container">
                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                  <h2 class="mb-3 text text-warning">Update Profile</h2>
                  <div class="mb-3">
                    <div class="view-img-properties rounded-circle" id="view-image"></div>
                        <label for="file-input" class="label-properties rounded-circle"><img class="icon-img rounded-circle" src="./assets/images/edit.webp"></label>
                        <input type="file" name="avatar" id="file-input" onchange="previewFile(this);"/>
                        <script>
                            let image_display=document.querySelector('#view-image')
                            let input_image=document.querySelector('#file-input')
                            uploaded_image="";
                            input_image.addEventListener('change',function(){
                                const reader= new FileReader();
                                reader.addEventListener('load',()=>{
                                    uploaded_image=reader.result;
                                    image_display.style.backgroundImage=`url(${uploaded_image})`;
                                })
                                reader.readAsDataURL(this.files[0]);
                            })
                        </script> 
                    </div>
                  <div class="mb-3">
                    <label for="email" class="mb-2 ps-1"><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="text" name="email" class="form-control" id="email" value="<?php if(isset($email)) echo $email;?>">
                  </div>
                  <div class="mb-3">
                    <label for="firstname" class="mb-2 ps-1"><i class="fa-solid fa-id-card-clip"></i> Firstname</label>
                    <input type="text" name="firstname" class="form-control" id="firstname" value="<?php if(isset($firstname)) echo $firstname;?>">
                  </div>
                  <div class="mb-3">
                    <label for="lastname" class="mb-2 ps-1"><i class="fa-solid fa-id-card-clip"></i> Lastname</label>
                    <input type="text" name="lastname" class="form-control" id="lastname" value="<?php if(isset($lastname)) echo $lastname;?>">
                  </div>
                  <div class="mb-3">
                    <label for="title" class="mb-2 ps-1"><i class="fa-solid fa-briefcase"></i> Title</label>
                    <input type="text" name="title" class="form-control" id="title" value="<?php if(isset($title)) echo $title;?>">
                  </div>
                  <div class="mb-3">
                    <label for="address" class="mb-2 ps-1"><i class="fa-solid fa-address-book"></i> Address</label>
                    <input type="text" name="address" class="form-control" id="address" value="<?php if(isset($address)) echo $address;?>">
                  </div>
                  <div class="mb-3">
                    <input type="hidden" name="hidden_id" class="form-control" value="<?php if(isset($id)) echo $id; ?>">
                  </div>
                  <button type="submit" name="updateBtn" class="signupBtn btn btn-warning">update</button>
                </form>
                <?php if(isset($result)) echo $result;?>
                <?php if(isset($form_errors)) echo show_errors($form_errors);?>
              </div>
            </div>

            <?php else:?>
            <div class="row d-flex justify-content-center align-items-center" style="height:80vh;">
                <div class="col-12 col-md-4 col-lg-3">
                    <h1 class="fw-bold"><span class="fs-2">You are not allowed to view this page ! <i class="fa-solid fa-ban fs-2"></i></span></h1>
                </div>
            </div>
            <?php endif?>
        </div>
    </div>
</body>
</html>