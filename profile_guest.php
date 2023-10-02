<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="assets/style_profile.css">
</head> 
<body>
    <div class="container PROFILE_GUEST">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="d-flex justify-content-center">
                    <img class="img-bg-properties mt-3" src="./assets/images/bg_default_user.webp" alt="">
                </div>

                <!-- show guest_user details profile -->
                <?php
                if($_SERVER['REQUEST_METHOD']=='GET'){
                    $guest_user=$_GET['guest_user'];
                
                    $sql="SELECT username,firstname,lastname,email,created_at,title,address,profile_pic FROM users WHERE username='$guest_user'";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die('invalid query'.$coonection->error);
                    }
                    while($res=$statement->fetch()){
                        $guest_username=$res['username'];
                        $firstname=$res['firstname'];
                        $lastname=$res['lastname'];
                        $email_address=$res['email'];
                        $join_date=strftime('%b %d %Y', strtotime($res['created_at']));
                        $profession=$res['title'];
                        $location=$res['address'];
                        $image=$res['profile_pic'];
                
                        $default="assets/images/default_user.webp";
                        if($image !=null){
                            $guest_image="assets/images/$image";
                            $guest_profile=$guest_image;
                        }else{
                            $guest_profile=$default;
                        }?>
                        <img class="img-user-properties rounded-circle" src="<?php if(isset($guest_profile)) echo $guest_profile;?>">
                        <div class="row d-flex justify-content-between">
                            <div class="col-12 col-md-8 col-lg-4">
                                <h1 class="User-info-area px-3">
                                <?php echo ucfirst($guest_username);?>
                                </h1>
                                <div class="User-info-area-credentials px-3">
                                    <p class="badge text text-dark rounded-pill text-bg-warning"><?php echo ucfirst($firstname);?></p>
                                    <p class="badge text text-dark rounded-pill text-bg-warning ms-2"><?php echo ucfirst($lastname);?></p>
                                </div>
                                <div class="User-info-area-description px-3">
                                    <p class="">
                                        <?php 
                                            if(isset($profession)){
                                                echo $profession;
                                            }else{
                                                echo  "<p class='text text-danger'>Please try to add your professional title !</p>";
                                            }
                                        ?>
                                    </p>
                                </div>
                                <div class="User-info-area-address px-3">
                                    <p><?php echo $location; ?> <a href="#">More info</a> <br>Email: <?php echo $email_address;?><br>Joined date: <?php echo $join_date;?></p>
                                </div>
                        <?php
                    }
                }
                ?>

                <!-- show counting posts and followers -->
                <?php
                    $sql="SELECT COUNT(*) as posts_nbr FROM posts WHERE user_post='$guest_username'";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die('invalid query!'.$connection->error);
                    }
                    while($res=$statement->fetch()){
                        $posts_count=$res['posts_nbr'];
                        ?>

                        <div class="User-info-area-address options-bar px-3">
                            <a class="btn badge p-2 px-3 rounded-pill text-bg-warning" href="#">Posts <span class="fw-bold"><?php echo $posts_count;?></span></a>
                        
                        <?php
                    }
                ?>
                            <a class="btn badge p-2 px-3 rounded-pill text-bg-warning" href="#">Followers <span class="fw-bold">10</span></a>
                            <a class="btn badge p-2 px-3 rounded-pill text-bg-warning" href="#">Following <span class="fw-bold">15</span></a>
                            <!-- <a class="btn badge text text-white p-2 rounded-pill text-bg-primary password-view" href="#"><i class="fa-solid fa-lock"></i> Edit Password</a> -->
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

</body>
</html>