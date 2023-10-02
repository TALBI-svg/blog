<?php
include_once 'components/headers.php';
include_once 'resources/db.php';
include_once 'resources/session.php';
include_once 'resources/utilities.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="assets/style_profile.css"> -->
</head>
<body>
    <div class="container p-3 px-4 mt-0 INDEX"> 
       <div class="row">
            <div class="col-12 col-md-12 col-lg-10">
                <div class="row posts-area">
                <?php
                    if(isset($_SESSION['id'])){
                        $id=$_SESSION['id'];
                        // Get "$username" from db
                        $username=getUsername($connection,$id); 

                        function isPostFavorited($conn,$us_name,$post_id){
                            $sql="SELECT SUM(status) as fave FROM favorites WHERE favorites_owner='$us_name' AND favorit_post_id='$post_id'";
                            $statement=$conn->query($sql);
                            if(!$statement){
                                die("invalid query for isPostLiked!").$conn->getMessage();
                            }
                            while($res=$statement->fetch()){
                               return $res;
                            }
                        }

                        $sql="SELECT COUNT(category_owner) as isCategorySelected FROM show_posts_by_category WHERE category_owner='$username'";
                        $statement=$connection->query($sql);
                        if(!$statement){
                            die("invalid qurey for CountCatego!").$connection->getMessage();
                        }
                        while($res=$statement->fetch()){
                            $isCategorySelected=$res['isCategorySelected'];
                            if($isCategorySelected!=1){
                                $sql="SELECT * FROM posts";
                                $statement=$connection->query($sql);
                                if(!$statement){
                                    die("invalid query".$connection->error);
                                }
                                while($res=$statement->fetch()){
                                    $description=substr($res['description'], 0, 25); 
                                    $title=substr($res['title'], 0, 34);
                                    $post_id=$res['id'];
                                    ?>
                                    <div class='col-12 col-md-6 col-lg-4'>
                                        <a class='nav-link' href='post_details.php?post_id=<?php echo $res['id'];?>'>
                                          <div class='card mb-3'>
                                            <div class='favorit-post-area'>
                                                <img src='assets/images_post/<?php echo $res['image'];?>' class='card-img-top' alt='...'>
                                                <?php 
                                                if(isset($_SESSION['id'])){
                                                    $res=isPostFavorited($connection,$username,$post_id); 
                                                    $fave=$res['fave'];
                                                    if($fave==1){
                                                        ?>
                                                        <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post-active'><i class='fa-solid fa-star fs-5 p-2 favorit-post text text-warning'></i></a>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post'><i class='fa-regular fa-star fs-5 p-2 favorit-post'></i></a>
                                                        <?php
                                                    }
                                                }else{
                                                    ?>
                                                    <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post'><i class='fa-regular fa-star fs-5 p-2 favorit-post'></i></a>
                                                    <?php
                                                }
                                                ?>
                                                
                                            </div>
                                            
                                            <div class='card-body'>
                                              <p class='card-title fs-6 text text-uppercase fw-bold'><?php echo $title;?></p>
                                              <p class='card-text description-post-area'><?php echo $description."...";?></p>
                                              <a href='post_details.php?post_id=<?php echo $post_id;?>' class='btn btn-warning  signupBtn'>Read more</a>
                                            </div>
                                          </div>
                                        </a>
                                    </div>
                                    <?php
                                }

                            }else{
                                $sql="SELECT * FROM show_posts_by_category WHERE category_owner='$username'";
                                $statement=$connection->query($sql);
                                if(!$statement){
                                    die("invalid qurey!").$connection->getMessage();
                                }
                                while($res=$statement->fetch()){
                                    $category_active=$res['category_active'];
                                    $sql="SELECT * FROM posts WHERE category='$category_active'";
                                    $statement=$connection->query($sql);
                                    if(!$statement){
                                        die("invalid query".$connection->error);
                                    }
                                    while($res=$statement->fetch()){
                                        $description=substr($res['description'], 0, 25); 
                                        $title=substr($res['title'], 0, 34);
                                        $post_id=$res['id'];
                                        ?>
                                        <div class='col-12 col-md-6 col-lg-4'>
                                            <a class='nav-link' href='post_details.php?post_id=<?php echo $res['id'];?>'>
                                              <div class='card mb-3'>
                                                <div class='favorit-post-area'>
                                                    <img src='assets/images_post/<?php echo $res['image'];?>' class='card-img-top' alt='...'>
                                                    <?php 
                                                    if(isset($_SESSION['id'])){
                                                        $res=isPostFavorited($connection,$username,$post_id); 
                                                        $fave=$res['fave'];
                                                        if($fave==1){
                                                            ?>
                                                            <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post-active'><i class='fa-solid fa-star fs-5 p-2 favorit-post text text-warning'></i></a>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post'><i class='fa-regular fa-star fs-5 p-2 favorit-post'></i></a>
                                                            <?php
                                                        }
                                                    }else{
                                                        ?>
                                                        <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post'><i class='fa-regular fa-star fs-5 p-2 favorit-post'></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class='card-body'>
                                                  <p class='card-title fs-6 text text-uppercase fw-bold'><?php echo $title;?></p>
                                                  <p class='card-text description-post-area'><?php echo $description."...";?></p>
                                                  <a href='post_details.php?post_id=<?php echo $post_id;?>' class='btn btn-warning  signupBtn'>Read more</a>
                                                </div>
                                              </div>
                                            </a>
                                        </div>
                                        <?php
                                    }    
                                }
                            }
                        }

                    }else{
                        $sql="SELECT * FROM posts";
                        $statement=$connection->query($sql);
                        if(!$statement){
                            die("invalid query".$connection->error);
                        }
                        while($res=$statement->fetch()){
                            $description=substr($res['description'], 0, 25); 
                            $title=substr($res['title'], 0, 34);
                            $post_id=$res['id'];
                            ?>
                            <div class='col-12 col-md-6 col-lg-4'>
                                <a class='nav-link' href='post_details.php?post_id=<?php echo $res['id'];?>'>
                                  <div class='card mb-3'>
                                    <div class='favorit-post-area'>
                                        <img src='assets/images_post/<?php echo $res['image'];?>' class='card-img-top' alt='...'>
                                        <a href='favorite_post.php?post_id=<?php echo $post_id;?>' class='nav-link favorit-post'><i class='fa-regular fa-star fs-5 p-2 favorit-post'></i></a>
                                    </div>
                                    
                                    <div class='card-body'>
                                      <p class='card-title fs-6 text text-uppercase fw-bold'><?php echo $title;?></p>
                                      <p class='card-text description-post-area'><?php echo $description."...";?></p>
                                      <a href='post_details.php?post_id=<?php echo $res['id'];?>' class='btn btn-warning  signupBtn'>Read more</a>
                                    </div>
                                  </div>
                                </a>
                            </div>
                            <?php
                        }
                    }
                    
                ?>        
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2 LATESTPOST">
                <h5 class="fw-bold">Latest Posts</h5>
                <!-- WHERE date_of_event >= NOW() - INTERVAL 1 HOUR
                <div class="container">
                    <div class="row mb-3 latest-area-content">
                        <div class="col-12 col-md-4 col-lg-4 p-0 pe-3">
                            <img src='assets/images/bg_default_user.webp' class='card-img-top rounded' alt='...'>
                        </div>
                        <div class="col-12 col-md-8 col-lg-8 p-0">
                            <span class="p-0">Test Post title</span><br>
                            <span class="date-time p-0 fw-light">6:33 am,20 Mar 2022</span>
                        </div>
                    </div>
                </div> -->

                <?php 
                    $sql="SELECT COUNT(*) as isLatestPostExist FROM posts WHERE date_create >= NOW() - INTERVAL 24 hour";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die("invalid query for latest posts".$connection->error);
                    }
                    while($res=$statement->fetch()){
                        $isLatestPostExist=$res['isLatestPostExist'];
                        if($isLatestPostExist>0){
                            // echo "ok";
                            $sql="SELECT * FROM posts  WHERE date_create >= NOW() - INTERVAL 24 hour LIMIT 5";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die("invalid query for latest posts".$connection->error);
                            }
                            while($res=$statement->fetch()){
                                $title=substr($res['title'], 0, 17);
                                $date=strftime("%b %d, %Y", strtotime($res['date_create']));
                                echo"
                                <div class='col-12 col-md-12 col-lg-12'>
                                    <div class='container'>
                                        <a href='post_details.php?post_id=$res[id]' class='row mb-3 latest-area-content link-redirection'>
                                            <div class='col-12 col-md-4 col-lg-4 p-0 pe-3'>
                                                <img src='assets/images_post/$res[image]' class='card-img-top' alt='...'>
                                            </div>
                                            <div class='col-12 col-md-8 col-lg-8 p-0'>
                                                <span class='p-0 fw-normal'>$title</span><br>
                                                <span class='date-time p-0 fw-light'>$date</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                ";
                            }
                        }else{
                            ?>
                            <p class="py-2 px-3 rounded text text-dark border" style="background-color:#f8f9fa;">No latest post for now !</p>
                            <?php
                        }
                    }

                ?>
                <?php
                if(isset($_SESSION['id'])){
                    ?>
                    <div class="col-12 col-md-12 col-lg-12 CATEGORIES">
                        <h5  class="fw-bold">Categories</h5>
                        <form action="" method="GET">
                            <select name="category" id="select" class="form-select" aria-label="Default select example">
                                <option selected disabled>Category</option>
                                <option value="front-end">Front-end</option>
                                <option value="back-end">Back-end</option>
                                <option value="data-base">Data-base</option>
                                <option value="all">All</option>
                            </select>
                        </form>
                        <script>
                            let choose=document.querySelector('#select')
                            choose.addEventListener('change',function(){
                               let result=choose.value
                               window.location='category_view.php?category='+result
                            })
                        </script>
                    </div>
                    <?php
                }
                ?>
            </div>
       </div>
    </div> 
</body>
</html>

