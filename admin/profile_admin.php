<?php
include_once 'admin_components/headers_admin.php';


if(isset($_SESSION['admin_id'])){
    $id=$_SESSION['admin_id'];
  
    $sql="SELECT * FROM admins WHERE id = :id";
    $statement=$connection->prepare($sql);
    $statement->execute(array(':id'=>$id));
  
    while($res= $statement->fetch()){
      $username_admin=$res['username_admin'];
      $email=$res['email'];
      $firstname=$res['firstname'];
      $lastname=$res['lastname'];
    //   $title=$res['title'];
    //   $address=$res['address'];
      $join_date= strftime("%b %d, %Y", strtotime($res['created_at']));
  
    }
    // add default image profile
    $user_image="../assets/images/".$username_admin.".jpg";
    $default="../assets/images/default_user.webp";
    if(file_exists($user_image)){
        $profile_image=$user_image;
    }else{
        $profile_image=$default;
    }

    $encode_id=base64_encode("encodeuserid{$id}");
}else{
    header("location:../login.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../assets/style_profile.css">
</head> 
<body>
    <div class="container Profile">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 first-container">
                <div class="d-flex justify-content-center">
                    <img class="img-bg-properties mt-3" src="../assets/images/bg_default_user.webp" alt="">
                </div>
                <img class="img-user-properties rounded-circle" src="<?php if(isset($profile_image)) echo $profile_image;?>">
                <a href="#"><img class="img-edit-properties rounded-circle" src="../assets/images/edit.webp"></a>
                <div class="row d-flex justify-content-between">
                    <div class="col-12 col-md-8 col-lg-5">
                        <h1 class="User-info-area px-3"><?php echo ucfirst($username_admin)?></h1>
                        <div class="User-info-area-credentials px-3">
                            <p class="badge text text-dark rounded-pill text-bg-warning"><?php echo ucfirst($firstname);?></p>
                            <p class="badge text text-dark rounded-pill text-bg-warning ms-2"><?php echo ucfirst($lastname);?></p>
                        </div>
                        <div class="User-info-area-description px-3">
                            <p class="">Title</p>
                        </div>
                        <div class="User-info-area-address px-3">
                            <p>Address <a href="#">More info</a> <br>Email: <?php echo $email;?><br>Joined date: <?php echo $join_date;?></p>
                        </div>
                        <div class="User-info-area-address options-bar px-3">
                            <a class="btn badge text text-dark p-2 rounded-pill bg-warning" href="create_user.php"><i class="fa-solid fa-user-plus"></i> Create User</a>
                            <a class="btn badge text text-dark p-2 rounded-pill bg-warning" href="#"><i class="fa-solid fa-chart-pie"></i> My Statics</a>
                            <a class="btn badge text text-dark p-2 rounded-pill bg-warning" href="#"><i class="fa-solid fa-user-pen"></i> Edit Profile</a>
                            <a class="btn badge text text-dark p-2 rounded-pill bg-warning mt-2" href="reset_password_admin.php"><i class="fa-solid fa-key"></i> Edit Password</a>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <script src="assets/main.js"></script>

</body>
</html>