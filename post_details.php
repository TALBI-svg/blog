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
    <title>Document</title>
</head>
<body>
    <div class="container POSTDETAILS">
        <div class='container global-post'>
            
            <!-- show post area -->
            <?php
                if($_SERVER['REQUEST_METHOD'] === "GET"){
                    $post_id=$_GET['post_id'];
                    $sql="SELECT * FROM posts WHERE id='$post_id'";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die("invalide query".$connection->error);
                    }
                    while($res=$statement->fetch()){
                        $title=$res['title'];
                        $user_post=$res['user_post'];
                        $default="default_user.webp";
                        $date_create=$date=strftime("%b %d, %Y", strtotime($res['date_create']));
                        $image_post=$res['image'];
                        $description=$res['description'];
                        $category=$res['category'];

                        $user_post_image=$res['user_post_image'];
                        $default="assets/images/default_user.webp";
                        
                        $profile_image=setDefaultImage($user_post_image,$default);
                        ?>
                        <div class='row'>
                        <div class='col-12 col-md-10 col-lg-6 m-auto p-0'>
                            <p class='title-area fw-bold fs-1 px-1'><?php echo $title; ?></p>
                            <div class='mb-3'>
                              <input type='text' hidden name='post_id' value='<?php echo $post_id;?>'>
                            </div>
                        </div>
                        </div>
                        
                        <div class='row post-owner-area d-flex justify-content-center p-0'>
                            <div class='col-12 col-md-10 col-lg-6 p-0'>
                                <div class='row'>
                                    <div class='container'>

                                        <!-- check which profile we have to redirect to on based on if user loged on or not? -->
                                        <?php 
                                        if(isset($_SESSION['username'])){
                                            $user_loged=$_SESSION['username'];

                                            if($user_loged===$user_post){
                                                ?>
                                                <a href='profile.php' class='col-12 col-md-4 col-lg-3 d-flex justify-content-start align-items-center nav-link'>
                                                    <img src='<?php echo $profile_image;?>' class='card-img-top rounded-circle' alt=''>
                                                    <div class='container'>
                                                        <div class='row ps-2'>
                                                            <div class='col-12 col-md-12 col-lg-12 p-0'>
                                                                <span class='p-0 fw-bold'><?php echo ucfirst($user_post);?></span><br>
                                                                <span class='date-time p-0 fw-ligh'><?php echo $date_create;?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                            }else{
                                                ?>
                                                <a href='profile_guest.php?guest_user=<?php echo $user_post;?>' class='col-12 col-md-4 col-lg-3 d-flex justify-content-start align-items-center nav-link'>
                                                    <img src='<?php echo $profile_image;?>' class='card-img-top rounded-circle' alt=''>
                                                    <div class='container'>
                                                        <div class='row ps-2'>
                                                            <div class='col-12 col-md-12 col-lg-12 p-0'>
                                                                <span class='p-0 fw-bold'><?php echo $user_post;?></span><br>
                                                                <span class='date-time p-0 fw-ligh'><?php echo $date_create;?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <?php
                                            }
                                        }else{
                                            ?>
                                            <a href='login.php' class='col-12 col-md-4 col-lg-3 d-flex justify-content-start align-items-center nav-link'>
                                                <img src='<?php echo $profile_image;?>' class='card-img-top rounded-circle' alt=''>
                                                <div class='container'>
                                                    <div class='row ps-2'>
                                                        <div class='col-12 col-md-12 col-lg-12 p-0'>
                                                            <span class='p-0 fw-bold'><?php echo $user_post;?></span><br>
                                                            <span class='date-time p-0 fw-ligh'><?php echo $date_create;?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php
                                           
                                        }
                                        ?>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class='row content-area d-flex justify-content-center'>
                            <div class='col-12 col-md-10 col-lg-6 p-0'>
                                <img src='assets/images_post/<?php echo $image_post;?>' class='card-img-top img-thumbnail rounded my-3' alt=''>
                                <p class='px-1'><?php echo $description;?></p>
                                <div class='row my-5'>
                                    <div class='col-12 col-md-6 col-lg-6'>
                                        <span>Category <a href='#' class='text text-dark'><span class='category-area p-3 py-2 fw-bold'><?php echo $category;?></span></a></span>
                                    </div>
                                </div>
                            <?php
                    }
                }
            ?>
        
                        
                    <!-- show count like-comment-shear-area  -->
                    <?php
                        if($_SERVER['REQUEST_METHOD'] === "GET"){
                            $post_id=$_GET['post_id'];

                            // Get the number of comments
                            $sql="SELECT COUNT(*) as comments_nbr FROM comments WHERE post_commented_id='$post_id'";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die("invalide query".$connection->error);
                            } 
                            while($response=$statement->fetch()){
                                $count_comments=$response['comments_nbr'];
                            ?>
                            <div class='row'>
                                <div class='col-12 col-md-12 col-lg-12 comments-counter-area d-flex justify-content-between'>
                                    <div class='col-6 col-md-4 col-lg-3 py-3 d-flex justify-content-between'>
                                    <?php
                                        if($_SERVER['REQUEST_METHOD'] === "GET"){
                                            $post_id=$_GET['post_id'];
                                            $likes_nbr=CountLikes($connection,$post_id);
                                        }
                                    ?>
                                        <div class='d-flex align-itmes-center likes-area'>
                                            <a href='like.php?post_id=<?php echo $post_id;?>' class='likeBtn text text-dark'>
                                                <!-- check if post liked or not to change 'inco-like' style -->
                                                <?php
                                                if(isset($_SESSION['id'])){   
                                                    if($_SERVER['REQUEST_METHOD'] === "GET"){
                                                        $post_id=$_GET['post_id'];
                                                        $id=$_SESSION['id'];
                                                        // Get "$username" from db
                                                        $like_owner=getUsername($connection,$id);

                                                        // check if post is liked 
                                                        $sql="SELECT SUM(like_post) as is_liked FROM likes WHERE like_owner='$like_owner' AND post_liked_id='$post_id'";
                                                        $statement=$connection->query($sql);
                                                        if(!$statement){
                                                            die("invalide query".$connection->error);
                                                        } 
                                                        while($res=$statement->fetch()){
                                                            $like_status=$res['is_liked'];
                                                            if($like_status==1){
                                                                ?>
                                                                <i class="fa-solid fa-heart fs-4 text text-danger"></i>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <i class="fa-regular fa-heart fs-4"></i>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    ?><i class="fa-regular fa-heart fs-4"></i><?php
                                                }
                                                ?>
                                            </a> 
                                            <span class='btn like-counter border-0 p-0 px-2 ms-2' data-bs-toggle="modal" data-bs-target="#exampleModal"><?php echo $likes_nbr;?></span>
                                            <!-- show user's likes post -->
                                            <?php
                                            $sql="SELECT COUNT(*) AS isLikesExist FROM likes WHERE post_liked_id=$post_id";
                                            $statement=$connection->query($sql);
                                            if(!$statement){
                                                die("invalid query!").$connection->getMessage();
                                            }
                                            while($res=$statement->fetch()){
                                                $status_likes=$res['isLikesExist'];
                                                if($status_likes>0){
                                                    ?>
                                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                        <div class="modal-content">
                                                          <!-- <div class="modal-header border border-white">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Likes</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                          </div> -->
                                                          <div class="modal-body">
                                                          <?php 
                                                          if($_SERVER['REQUEST_METHOD'] === "GET"){
                                                              $post_id=$_GET['post_id'];
                                                              
                                                              function getUserTitle($conn,$like_owner){
                                                                $sql="SELECT * FROM users WHERE username='$like_owner'";
                                                                $statement=$conn->query($sql);
                                                                if(!$statement){
                                                                    die('invalid query!').$conn->getMessage();
                                                                }
                                                                while($res=$statement->fetch()){
                                                                    $title=$res['title'];
                                                                    return $title;
                                                                }
                                                              }
                                                              $sql="SELECT * FROM likes WHERE post_liked_id=$post_id";
                                                              $statement=$connection->query($sql);
                                                              if(!$statement){
                                                                  die("invalid query for count likkes".$connection->getMessage());
                                                              }
                                                              while($res=$statement->fetch()){
                                                                  $like_owner=$res['like_owner'];
                                                                  $like_owner_img=$res['like_owner_img'];
                                                                  $user_title=getUserTitle($connection,$like_owner);

                                                                  $default="assets/images/default_user.webp";
                                                                  if($like_owner_img !=null){
                                                                      $image="assets/images/$like_owner_img";
                                                                      $user_like_img=$image;
                                                                  }else{
                                                                      $user_like_img=$default;
                                                                  }
                                                                  ?>
                                                                  <div class="col-12 col-md-12 col-lg-12 d-flex align-items-center p-2 mb-2 rounded show-user-like-area">
                                                                      <img class="img-bg-properties rounded-circle" src="<?php echo $user_like_img;?>" alt="">
                                                                      <div class="d-column">
                                                                        <span class='ps-2'><?php echo ucfirst($user_title);?></span><br>
                                                                        <span class='ps-2'><?php echo ucfirst($like_owner);?></span>
                                                                      </div>
                                                                  </div>
                                                                  <?php
                                                              }
                                                          }
                                                          ?>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <?php
                                                }else{
                                                    // echo "no likes";
                                                }
                                            }
                                            ?>
                                            
                                        </div>
                                        <span class='likeBtn d-flex align-itmes-center'><i class='fa-regular fa-comment fs-4'></i> <span class='comment-counter px-2 ms-2'><?php echo $count_comments;?></span></span>
                                    </div>
                                    <div class='col-6 col-md-4 col-lg-3 py-3 d-flex justify-content-end'>
                                        <span class='likeBtn d-flex align-itmes-center' id='shear-post'><i class='fa-solid fa-share fs-4'></i> <span class='shear-counter px-2 ms-2'>10</span></span>
                                        <?php
                                        $url=$_SERVER['REQUEST_URI'];
                                        echo "
                                            <script>
                                                var shearBtn=document.querySelector('#shear-post')
                                                shearBtn.addEventListener('click', function(){
                                                    let copied_ele='http://localhost/'+'$url'
                                                    console.log(copied_ele)
                                                    navigator.clipboard.writeText(copied_ele)
                                                })                                            
                                            </script>
                                        ";
                                        ?>
                                    </div> 
                                </div>
                            </div>
                            <?php
                            }
                        }
                    ?>

                            <?php
                                if(isset($_SESSION['id'])){
                                    $id=$_SESSION['id'];
                                    $username=getUsername($connection,$id);

                                    // Get "$username" from db
                                    $sql="SELECT * FROM users WHERE id = :id";
                                    $statement=$connection->prepare($sql);
                                    $statement->execute(array(':id'=>$id));
                                    while($res= $statement->fetch()){
                                      $username=$res['username'];
                                      $profile_pic=$res['profile_pic'];
                                    }      

                                    if(isset($_POST['createCommentBtn'])){
                                        $post_id=$_POST['post_id'];
                                    
                                        if(!empty($_POST['comment'])){
                                            $comment=strip_tags($_POST['comment']);
                                            $comment_owner=$username;
                                            $comment_owner_img=$profile_pic;
                                        
                                            try{
                                                $sql="INSERT INTO comments (comment_text, comment_time, comment_owner, comment_owner_img, post_commented_id) 
                                                VALUES (:comment_text, now(), :comment_owner, :comment_owner_img, :post_commented_id)";
                                                $statement=$connection->prepare($sql);
                                                $statement->execute(array(':comment_text'=>$comment, ':comment_owner'=>$comment_owner, ':comment_owner_img'=>$comment_owner_img, ':post_commented_id'=>$post_id));
                                            
                                                if($statement->rowCount()==1){
                                                    header("location:post_details.php?post_id=".$post_id);
                                                    // push notifications
                                                    $user_post=getUserPost($connection,$post_id);
                                                    $post_title=getPostTitle($connection,$post_id);
                                                    $event="comment";
                                                    $content="your post";
                                                    $noti_sender=$comment_owner;
                                                    $noti_sender_img=$comment_owner_img;
                                                    $noti_receiver=$user_post;
                                                    NotiFromUser($connection,$event,$content,$noti_sender,$noti_sender_img,$noti_receiver,$post_id,$post_title);                                                   
                                                }
                                            }catch(PDOException $ex){
                                                echo "Error Exception ".$ex->getMessage();
                                            }
                                        
                                        }else{
                                            // issue to show message 
                                            header("location:post_details.php?post_id=".$post_id);
                                        }
                                    }
                                
                                }
                            ?>

                            <!-- show form add comment area -->
                            <?php if(isset($_SESSION['id'])):?>
                                <div class='row'>
                                    <div class='col-12 col-md-12 col-lg-12 comments-create-area py-3'>
                                        <form action='' method='POST' class='d-flex justify-content-between align-items-center' autocomplete='off'>
                                            <div class='col-6 col-md-6 col-lg-6'>
                                                <input type='text' name='comment' class='form-control' placeholder='Add your comment here'>
                                                <input type='text' name='post_id' hidden value='<?php echo $post_id;?>' class='form-control'>
                                            </div>
                                            <button type='submit' name='createCommentBtn' class='signupBtn btn btn-warning'>Comment</button>
                                        </form>
                                    </div>
                                </div>
                            <?php else:?>
                            <?php endif;?>

                            <!-- show comment area -->
                            <?php
                                if($_SERVER['REQUEST_METHOD'] === "GET"){
                                    $post_id_comment=$_GET['post_id'];


                                    function getCommentToUpdate($conn,$comment_id){
                                        $sql="SELECT * FROM comments WHERE id=$comment_id";
                                        $statement=$conn->query($sql);
                                        if(!$statement){
                                            die('invalid query!').$conn->getMessage();
                                        }
                                        while($res=$statement->fetch()){
                                            return $res;
                                        }
                                    }

                                    // // Get comments informations
                                    $sql="SELECT * FROM comments WHERE post_commented_id='$post_id'";
                                    $statement=$connection->query($sql);
                                    if(!$statement){
                                        die("invalide query".$connection->error);
                                    }
                                    while($res=$statement->fetch()){
                                        $comment_id=$res['id'];
                                        $comment_owner=$res['comment_owner'];
                                        $comment_image=$res['comment_owner_img'];
                                        // $default="assets/images/default_user.webp";
                                        // if($comment_image !=null){
                                        //     $user_comment_image="assets/images/$comment_image";
                                        // }else{
                                        //     $user_comment_image=$default;
                                        // }
                                        $comment=$res['comment_text'];
                                        // check is comment 'url' or 'string'
                                        $comment_content;
                                        if (filter_var($comment, FILTER_VALIDATE_URL)) {
                                            $url_comment="<a href=".$comment.">check url</a>";
                                            $comment_content=trim($url_comment);
                                        } else {
                                            $comment_content=trim($comment);
                                        }
                                        $comment_date=strftime("%b %d, %Y | %H : %M", strtotime($res['comment_time']));

                                ?>
                                <div class='row'>
                                    <div class='col-12 col-md-12 col-lg-12 show-comment-area py-0'>
                                        <div class='col-12 col-md-6 col-lg-6 d-flex justify-content-start align-items-start my-3'>
                                            <?php 
                                            if(isset($_SESSION['username'])){
                                                $user_loged=$_SESSION['username'];
                                                if($user_loged===$comment_owner){
                                                    ?>
                                                    <a href='profile.php' class=''>
                                                        <img src='assets/images/<?php echo $comment_image;?>' class='card-img-top rounded-circle p-0 me-3' alt=''>
                                                    </a>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <a href='profile_guest.php?guest_user=<?php echo $comment_owner;?>' class=''>
                                                        <img src='assets/images/<?php echo $comment_image;?>' class='card-img-top rounded-circle p-0 me-3' alt=''>
                                                    </a>
                                                    <?php
                                                }
                                            }else{
                                                ?>
                                                <a href='login.php' class=''>
                                                    <img src='assets/images/<?php echo $comment_image;?>' class='card-img-top rounded-circle p-0 me-3' alt=''>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <div class='comments-text-area pt-1 p-2 rounded'>
                                                <div class='comments-title-area d-flex justify-content-between align-items-start'>
                                                    <p class='m-0'><?php echo  $comment_owner;?></p>
                                                    <div class='dropdown'>
                                                      <?php
                                                        if(isset($_SESSION['id'])){
                                                            $id=$_SESSION['id'];
                                                            $userLogged=getUsername($connection,$id);
                                                            if($userLogged==$comment_owner){
                                                                // echo "ok";
                                                                ?>
                                                                <a class='show-more-btn' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis text text-dark'></i></a> 
                                                                <?php
                                                            }else{
                                                                // echo "err";
                                                            }
                                                        }else{
                                                            // echo "user not logged!";
                                                        }
                                                      ?>     
                                                      <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' id='update_comment'>Update comment</a></li>
                                                        <li><a class='dropdown-item' href='delete_comment.php?comment_id=<?php echo $comment_id;?>'>Delete comment</a></li>
                                                      </ul>
                                                    </div>
                                                </div>
                                                <p class='mb-1 fw-normal comment-content-area'><?php echo $comment_content;?></p>
                                                <p class='m-0 fw-light comment-date-area'><?php echo $comment_date;?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if(isset($_SESSION['id'])){
                                        // $id=$_SESSION['id'];
                                        // $username=getUsername($connection,$id); 

                                        $res=getCommentToUpdate($connection,$comment_id);
                                        $comment_id=$res['id'];
                                        $comment_text=$res['comment_text'];
                                        // $post_id=$res['post_commented_id'];

                                        // update_comment
                                        // if(isset($_POST['updateCmBtn'])){
                                        //     header("location:update.php?post_id=$comment_id");
                                        // }
                                    }
                                    ?>
                                    <div class='col-12 col-md-12 col-lg-12 update-comment-area py-3'>
                                        <form action='update_comment.php' method='POST' class='d-flex justify-content-between align-items-center' autocomplete='off'>
                                            <div class='col-6 col-md-6 col-lg-6'>
                                                <input type='text' name='comment' value='<?php echo $comment_text;?>' class='form-control comment-content' placeholder='Update your comment here'>
                                                <input type='text' name='comment_id_hidden' hidden value='<?php echo $comment_id;?>' class='form-control'>
                                            </div>
                                            <button type='submit' name='updateCmtBtn' class='signupBtn btn btn-warning'>Update</button>
                                        </form>
                                    </div>
                                </div>
                                <?php
                                }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
                    
    </div>
    <script src="assets/main.js"></script>
</body>
</html>