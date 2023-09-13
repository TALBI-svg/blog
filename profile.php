<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';


if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
  
    $sql="SELECT * FROM users WHERE id = :id";
    $statement=$connection->prepare($sql);
    $statement->execute(array(':id'=>$id));
  
    while($res= $statement->fetch()){
      $username=$res['username'];
      $email=$res['email'];
      $firstname=$res['firstname'];
      $lastname=$res['lastname'];
      $title=$res['title'];
      $address=$res['address'];
      $created_at= strftime("%b %d, %Y", strtotime($res['created_at']));
  
    }
    // add default image profile
    $user_image="assets/images/".$username.".jpg";
    $default="assets/images/default_user.webp";
    if(file_exists($user_image)){
        $profile_image=$user_image;
    }else{
        $profile_image=$default;
    }

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
    <title></title>
    <link rel="stylesheet" href="assets/style_profile.css">
</head> 
<body>
    <div class="container Profile">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 first-container">
                <div class="d-flex justify-content-center">
                    <img class="img-bg-properties mt-3" src="./assets/images/bg_default_user.webp" alt="">
                </div>
                <img class="img-user-properties rounded-circle" src="<?php if(isset($profile_image)) echo $profile_image;?>">
                <a href="edit_profile.php"><img class="img-edit-properties rounded-circle" src="./assets/images/edit.webp"></a>
                <div class="row d-flex justify-content-between">
                    <div class="col-12 col-md-8 col-lg-4">
                        <h1 class="User-info-area px-3">
                        <?php echo ucfirst($username);?>
                        </h1>
                        <div class="User-info-area-credentials px-3">
                            <p class="badge text text-white rounded-pill text-bg-warning"><?php echo ucfirst($firstname);?></p>
                            <p class="badge text text-white rounded-pill text-bg-warning ms-2"><?php echo ucfirst($lastname);?></p>
                        </div>
                        <div class="User-info-area-description px-3">
                            <p class="">
                                <?php 
                                    if(isset($title)){
                                        echo $title;
                                    }else{
                                        echo  "<p class='text text-danger'>Please try to add your professional title !</p>";
                                    }
                                ?>
                            </p>
                        </div>
                        <div class="User-info-area-address px-3">
                            <p><?php 
                                if(isset($address)){ 
                                    echo $address;
                                }else{ 
                                    echo "<span class='text text-danger'>Try to add your address !</span>";
                                }?> <a href="#">More info</a> <br>Email: <?php echo $email;?><br>Joined date: <?php echo $created_at;?></p>
                        </div>
                        <div class="User-info-area-address options-bar px-3">
                            <a class="btn badge text text-white p-2 rounded-pill text-bg-dark" href="create_post.php"><i class="fa-solid fa-circle-plus"></i> Create Post</a>
                            <a class="btn badge text text-white p-2 rounded-pill text-bg-dark" href="my_posts.php"><i class="fa-solid fa-newspaper"></i> My Posts</a>
                            <a class="btn badge text text-white p-2 rounded-pill text-bg-dark" href="edit_profile.php?user_identity==<?php if(isset($encode_id)) echo $encode_id;?>"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</a>
                            <a class="btn badge text text-white p-2 rounded-pill text-bg-dark mt-2" href="reset_password.php"><i class="fa-solid fa-lock"></i> Edit Password</a>
                        </div>
                    </div>
                </div>
                    <?php
                    if(isset($_SESSION['id'])){
                        $id=$_SESSION['id'];
                        // get the username from db
                        $username=getUsername($connection,$id);

                        // get comments for each post
                        function getComments($conn,$us_name,$post_id){
                            $sql="SELECT comments.* FROM comments LEFT OUTER JOIN posts ON comments.post_commented_id='$post_id' WHERE posts.user_post='$us_name' group by comments.comment_time DESC";
                            $statement=$conn->query($sql);
                            if(!$statement){
                                die("invalide query".$conn->error);
                            } 
                            while($res=$statement->fetch()){
                                return $res;
                            }
                        }

                        function commentsCount($conn,$post_id){
                            $sql="SELECT COUNT(*) as comments_nbr FROM comments WHERE post_commented_id='$post_id'";
                            $statement=$conn->query($sql);
                            if(!$statement){
                                die("invalide query".$conn->error);
                            } 
                            while($response=$statement->fetch()){
                                return $count_comments=$response['comments_nbr'];
                            }
                        }

                        function likesCount($conn,$post_id){
                            $sql="SELECT COUNT(like_post) as likes_nbr FROM likes WHERE post_liked_id=$post_id";
                            $statement=$conn->query($sql);
                            if(!$statement){
                                die("invalid query for count likkes".$conn->getMessage());
                            }
                            while($res=$statement->fetch()){
                                return $likes_nbr=$res['likes_nbr'];
                            }
                        }

                        function isPostLiked($conn,$us_name,$post_id){
                            // check if post is liked 
                            $sql="SELECT SUM(like_post) as is_liked FROM likes WHERE like_owner='$us_name' AND post_liked_id='$post_id'";
                            $statement=$conn->query($sql);
                            if(!$statement){
                                die("invalide query".$conn->error);
                            } 
                            while($res=$statement->fetch()){
                                return $res;
                            }
                        }
                        
                        // get each post
                        $sql="SELECT * FROM posts WHERE user_post=:user_post order by  date_create DESC";
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':user_post'=>$username));
                        while($res=$statement->fetch()){
                            $post_id=$res['id'];
                            $title=$res['title'];
                            $description=substr($res['description'], 0, 300); 
                            $image=$res['image'];
                            $date_create=strftime("%b %d, %Y", strtotime($res['date_create']));
                            $category=$res['category'];
                            $user_post=ucfirst($res['user_post']);

                            $user_post_image=$res['user_post_image'];
                            $default="assets/images/default_user.webp";
                            if($user_post_image !=null){
                                $user_image="assets/images/$user_post_image";
                                $profile_image=$user_image;
                            }else{
                                $profile_image=$default;
                            }

                            // print_r(getComments($connection,$username,$post_id));
                            ?>
                            <div class="row post-area-conatiner">
                            <div class="col-12 col-md-5 col-lg-5 post-area p-3 mb-3 rounded">
                                <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-between align-items-start post-area-owner">
                                    <div class="col-6 col-md-6 col-lg-6 d-flex left-area">
                                          <img class="left-area-image rounded-circle" src="<?php echo $profile_image;?>" alt="">
                                          <div class="left-area-info ps-2">
                                              <span class="p-0 user"><?php echo $user_post;?></span><br>
                                              <span class="p-0 date"><?php echo $date_create;?></span>
                                          </div>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end right-area">
                                       <div class='dropdown'>
                                           <a class='show-more-btn d-flex align-items-start' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis text text-dark'></i></a>      
                                           <ul class='dropdown-menu'>
                                               <li><a class='dropdown-item' href='edit_post.php?post_id=<?php echo $post_id;?>'>Update</a></li>
                                               <li><a class='dropdown-item' href='my_posts.php'>Delete</a></li>
                                               <li><a class='dropdown-item' href='#'>Something else here</a></li>
                                           </ul>
                                       </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 post-area-title mt-3 mb-3">
                                    <h4 class=""><?php echo $title;?></h4>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 post-area-image mb-2">
                                    <img class="img-fluid rounded" src="assets/images_post/<?php echo $image;?>" alt="">
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 post-area-content mb-2">
                                    <p class="text text-start"><?php echo $description."<a href='post_details.php?post_id=$post_id'> read more</a>";?></p>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12 post-area-category mb-2">
                                    <p class="">Category <span class="p-1 px-2 fw-bold"><?php echo $category;?></span></p>
                                </div> 

                                <div class="col-12 col-md-12 col-lg-12 d-flex post-area-cls py-2 mb-3">
                                    <div class="col-6 col-md-6 col-lg-6 d-flex align-items-center post-area-cls-left">  
                                        <?php $likes_nbr=likesCount($connection,$post_id); ?>
                                        <div class="col-3 col-md-3 col-lg-3 d-flex post-area-cls-left-like">
                                            <p class="p-0 m-0 d-flex align-items-center">
                                                <?php 
                                                if(isset($_SESSION['id'])){
                                                    $res=isPostLiked($connection,$username,$post_id);
                                                    $like_status=$res['is_liked'];
                                                    if($like_status==1){
                                                        ?>
                                                        <i class="fa-solid fa-heart me-2 fs-5 text text-danger"></i>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <i class="fa-regular fa-heart me-2 fs-5"></i>
                                                        <?php
                                                    }
                                                }else{
                                                    ?><i class="fa-regular fa-heart me-2 fs-5"></i><?php
                                                }
                                                ?>
                                                <span><?php echo $likes_nbr;?></span></p>
                                        </div>

                                        <?php $comment_nbr=commentsCount($connection,$post_id); ?>
                                        <div class="col-6 col-md-6 col-lg-6 d-flex post-area-cls-right-comment">
                                            <p class="p-0 m-0 d-flex align-items-center"><i class='fa-regular fa-comment fs-5 pe-2'></i> <span><?php echo $comment_nbr;?></span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end post-area-cls-right">
                                        <p class="p-0 m-0 d-flex align-items-center"><i class='fa-solid fa-share fs-5 pe-2'></i> <span>10</span></p>
                                    </div>
                                </div>

                                <?php            
                                    $res=getComments($connection,$username,$post_id); 
                                    if($res!=null){
                                        $comment_owner=$res['comment_owner'];
                                        $comment_image=$res['comment_owner_img'];
                                        // $default="assets/images/default_user.webp";
                                        // if($comment_image !=null){
                                        //     $user_comment_image="assets/images/$comment_image";
                                        // }else{
                                        //     $user_comment_image=$default;
                                        // }
                                        // check is comment 'url' or 'string'
                                        $comment=$res['comment_text'];
                                        $comment_content;
                                        if (filter_var($comment, FILTER_VALIDATE_URL)) {
                                            $url_comment="<a href=".$comment.">sheered url</a>";
                                            $comment_content=$url_comment;
                                        } else {
                                            $comment_content=$comment;
                                        }
                                        $comment_date=strftime("%b %d, %Y | %H : %M", strtotime($res['comment_time']));
                                        ?>
                                        <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-start align-items-start post-area-show-comment mb-2 p-0">
                                            <img class="card-img-top rounded-circle p-0 me-3" src="assets/images/<?php echo $comment_image;?>" alt="">
                                            <div class='comments-area pt-1 p-2 rounded'>
                                                <div class='comments-title-area d-flex justify-content-between align-items-start'>
                                                    <p class='m-0'><?php echo $comment_owner;?></p>
                                                    <div class='dropdown m-0 p-0'>
                                                      <a class='show-more-btn' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis text text-dark'></i></a>      
                                                      <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='#'>Action</a></li>
                                                        <li><a class='dropdown-item' href='#'>Another action</a></li>
                                                        <li><a class='dropdown-item' href='#'>Something else here</a></li>
                                                      </ul>
                                                    </div>
                                                </div>
                                                <p class='mb-1 fw-normal comment-content-area'><?php echo $comment_content;?></p>
                                                <p class='m-0 fw-light comment-date-area'><?php echo $comment_date;?></p>
                                                <span><a class="nav-link text text-center mt-1 show-more-comments" href='post_details.php?post_id=<?php echo $post_id;?>'>show more comments</a></span>
                                            </div>
                                        </div>
                                        <?php
                                    }else{
                                        echo "<p class='fw-bold'>No comments yet! <span class='fw-normal'>be the first to comment</span></p>";
                                    }
                                ?>

                            </div>
                            </div> 
                            <?php
                        }
                    }
                    ?>   
            </div> 
        </div>
    </div>
    <script src="assets/main.js"></script>

</body>
</html>