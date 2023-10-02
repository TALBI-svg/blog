<?php
include_once './resources/session.php';
include_once './resources/utilities.php';
include_once './resources/db.php';


if(isset($_SESSION['id'])){
  $id=$_SESSION['id'];
  $username=getUsername($connection,$id);
  
  // add default image profile
  $user_image="assets/images/".$username.".jpg";
  $default="assets/images/default_user.webp";
  if(file_exists($user_image)){
      $profile_image=$user_image;
  }else{
      $profile_image=$default;
  }
 
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="./assets/icons/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/css/all.min.css">
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/style_profile.css">
</head>
<body>
    <div class="container HEADER">
      <nav class="navbar navbar-expand-lg bg-body">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">Blog</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="col-12 d-flex justify-content-between align-items-start align-items-lg-end mt-2 mt-lg-0">
            <div class="col-4 d-flex align-items-lg-center navbar-nav">
              <a class="nav-link" href="#">Last_blogs</a>
              <?php if(isset($_SESSION['username']) || isCookieValid($connection)) : ?>
                <a class="nav-link" href="favorite_post_view.php">Favorites</a>
                <a class="nav-link" href="my_posts.php">My Post's</a>
                <a class="nav-link btn border border-white py-1 signupBtn d-flex align-items-center search-area" href="search.php">Search <i class="fa-solid fa-magnifying-glass ms-2"></i></a>
              <?php else: ?>
                <!-- <a class="nav-link" href="login.php">login</a> -->
              <?php endif ?>
  
            </div>
            <div class="col-8 col-lg-2 navbar-nav ms-auto info-user-area d-flex justify-content-end align-items-center">
              <?php if(isset($_SESSION['username']) || isCookieValid($connection)) : ?>
                <div class="d-flex justify-content-end ms-auto align-items-center">
                  <!-- push notification -->
                  <?php
                  $sql="SELECT COUNT(*) AS newNoti FROM notifications WHERE readed='false' and noti_receiver='$username'";
                  $statement=$connection->query($sql);
                  if(!$statement){
                    die("invalid query for newNoti!").$connection->getMessage();
                  }
                  while($res=$statement->fetch()){
                    $newNoti=$res['newNoti'];
                    if($newNoti>0){
                      ?>
                        <a href='notifications.php' class='add-post-area nav-link me-0 d-flex justify-content-center rounded-circle align-items-center'><i class='fa-regular fa-bell'></i></a>    
                        <span class='noti-area rounded-circle d-flex justify-content-center align-items-center'><?php echo $newNoti;?></span>
                      <?php
                    }else{
                      ?>
                      <a href='notifications.php' class='add-post-area nav-link me-3 d-flex justify-content-center rounded-circle align-items-center'><i class='fa-regular fa-bell'></i></a> 
                      <?php
                    }
                  }
                  ?>
                  
                  <a href="create_post.php" class="add-post-area nav-link me-3 d-flex justify-content-center rounded-circle align-items-center"><i class="fa-solid fa-plus"></i></a>
                  <a class="profile-link-area" href="profile.php"><img class="img-user-properties rounded-circle me-2" src="<?php if(isset($profile_image)) echo $profile_image;?>"></a>
                  <a class="signupBtn btn btn-danger" href="logout.php">logout</a>
                </div>
              <?php else: ?>
                <a class="signupBtn btn btn-warning" href="signup.php">signup</a>
                <a class="nav-link" href="login.php">login</a>
              <?php endif ?>
            </div></div>

          </div>
        </div>
      </nav>
    </div>
    <script src="assets/icons/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>