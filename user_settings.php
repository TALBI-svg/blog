<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $encode_id=base64_encode("encodeuserid{$id}");


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
<div class="container USERSETTINGS">
       <div class="container">
            <div class="row title-area mt-3 mb-2">
            <h3 class="d-flex align-items-center"><a class="nav-link pe-2" href="profile.php">Profile</a> > <a class="nav-link px-2" href="user_settings.php">Settings</a></h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <a class="btn badge text text-dark p-2 rounded-pill border-0 text-bg-warning mt-2" href="feedback.php"><i class="fa-solid fa-comment-dots"></i> Feedback</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3 mt-2">
                    <a class="btn badge text text-dark p-2 rounded-pill border-0 text-bg-warning" href="edit_profile.php?user_identity==<?php if(isset($encode_id)) echo $encode_id;?>"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3 mt-2">
                <a class="btn badge text text-dark p-2 rounded-pill border-0 text-bg-warning" href="reset_password.php"><i class="fa-solid fa-lock"></i> Edit Password</a>
                </div>
            </div>
       </div>
    </div>
    
</body>
</html>