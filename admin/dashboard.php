<?php
include_once 'admin_components/headers_admin.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container p-3 px-4 mt-0 DASHBOARD">
        <div class="row">
            <div class="left-side d-column mt-3 col-12 col-md-8 col-lg-8"> 
                <div class="top d-flex">
                    <div class="col-3 col-md-3 col-lg-3 me-1">
                        <?php 
                        if(isset($_SESSION['admin_id'])){
                            $id=$_SESSION['admin_id'];
                                $username_admin=getAdminUsername($connection,$id);

                            $sql="SELECT COUNT(*) as usersCount FROM users";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die("invalid query!").$connection->getMessage();
                            }
                            while($res=$statement->fetch()){
                                $users_count=$res['usersCount'];
                                // echo $users_count;
                                ?>
                                <!-- <a href="./panel_options.php"> -->
                                <div class="card-container border rounded p-2 bg-primary text text-white shadow-md">
                                    <p><i class="fa-solid fa-users"></i><span class="ms-3 text-card-area">Users</span></p>
                                    <p><?php echo $users_count;?></p>
                                </div>
                                <!-- </a> -->
                                <?php
                            }
                        }else{
                            header('location:../login.php');
                        }
                        ?>
                    </div>
                    <div class="col-3 col-md-3 col-lg-3 me-1">
                        <?php 
                        if(isset($_SESSION['admin_id'])){
                            $id=$_SESSION['admin_id'];
                            $username_admin=getAdminUsername($connection,$id);

                            $sql="SELECT COUNT(*) as postsCount FROM posts";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die("invalid query!").$connection->getMessage();
                            }
                            while($res=$statement->fetch()){
                                $posts_count=$res['postsCount'];
                                // echo $users_count;
                                ?>
                                <!-- <a href="./panel_options.php"> -->
                                <div class="card-container border rounded p-2 bg-secondary text text-white shadow-md">
                                    <p><i class="fa-solid fa-newspaper"></i><span class="ms-3 text-card-area">Posts</span></p>
                                    <p><?php echo $posts_count;?></p>
                                </div>
                                <!-- </a> -->
                                <?php
                            }
                        }else{
                            header('location:../login.php');
                        }
                        ?>
                    </div>
                    <div class="col-3 col-md-3 col-lg-3 me-1">
                        <div class="card-container border rounded p-2 bg-info text text-white shadow-md">
                            <p><i class="fa-solid fa-people-roof"></i><span class="ms-3 text-card-area">Groups</span></p>
                            <p>2</p>
                        </div>
                    </div>
                    <div class="col-3 col-md-3 col-lg-3 me-1">
                        <?php 
                        if(isset($_SESSION['admin_id'])){
                            $id=$_SESSION['admin_id'];
                            $username_admin=getAdminUsername($connection,$id);

                            $sql="SELECT COUNT(*) as feedbacksCount FROM feedbacks";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die("invalid query!").$connection->getMessage();
                            }
                            while($res=$statement->fetch()){
                                $feedbacksCount=$res['feedbacksCount'];
                                // echo $users_count;
                                ?>
                                <!-- <a href="./panel_options.php"> -->
                                <div class="card-container border rounded p-2 bg-secondary text text-white shadow-md">
                                    <p><i class="fa-solid fa-comment-dots"></i><span class="ms-3 text-card-area-last">Feedbacks</span></p>
                                    <p><?php echo $feedbacksCount;?></p>
                                </div>
                                <!-- </a> -->
                                <?php
                            }
                        }else{
                            header('location:../login.php');
                        }
                        ?>
                    </div>
                </div>
                <div class="bottom">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="post-category-counting mt-4">
                            <p class="fw-bold">Count posts for each category</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-side mt-3 col-12 col-md-4 col-lg-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <?php
                    if(isset($_SESSION['admin_id'])){
                        $admin_id=$_SESSION['admin_id'];
                        $username_admin=getAdminUsername($connection,$admin_id);
                    
                        $sql="SELECT COUNT(*) isLikesExists FROM likes";
                        $statement=$connection->query($sql);
                        if(!$statement){
                            die("invalid query!").$connection->getMessage();
                        }
                        while($res=$statement->fetch()){
                            $isLikesExists=$res['isLikesExists'];
                            if($isLikesExists>0){
                                // echo "ok";
                                function getPostMostLiked($conn){
                                    $sql="SELECT l.post_liked_id, count(*) as cnt 
                                    FROM likes l
                                    GROUP BY l.post_liked_id 
                                    ORDER BY cnt DESC 
                                    LIMIT 1";
                                    $statement=$conn->query($sql);
                                    if(!$statement){
                                        die("invalid query!").$conn->getMessage();
                                    }
                                    while($res=$statement->fetch()){
                                        $id=$res['post_liked_id'];
                                        return $id;
                                    }
                                }
                                $postML_id=getPostMostLiked($connection);
                                ?>

                                <div class="card-container post-most-liked border rounded p-2 shadow-md">
                                    <?php
                                    if(isset($_SESSION['admin_id'])){
                                        $admin_id=$_SESSION['admin_id'];
                                        $username_admin=getAdminUsername($connection,$admin_id);
                                        $sql="SELECT * FROM posts WHERE id='$postML_id'";
                                        $statement=$connection->query($sql);
                                        if(!$statement){
                                            die("invalid query!").$connection->getMessage();
                                        }
                                        while($res=$statement->fetch()){
                                            $title=$res['title'];
                                            $image=$res['image'];
                                            $desciption=substr($res['description'], 0,300);
                                            $category=$res['category'];
                                            $user_post=ucfirst($res['user_post']);
                                            $date_create=strftime("%b %d, %Y", strtotime($res['date_create']));
                                        }
                                    }

                                    function getUserID($conn,$username){
                                        $sql="SELECT * FROM users WHERE username='$username'";
                                        $statement=$conn->query($sql);
                                        if(!$statement){
                                            die("invalid query!").$conn->getMessage();
                                        }
                                        while($res=$statement->fetch()){
                                            $user_id=$res['id'];
                                            return $user_id;
                                        }
                                    }

                                    $user_id=getUserID($connection,$user_post);
                                    // echo $user_id;
                                    ?>
                                    <p><i class="fa-solid fa-chart-line"></i><span class="ms-3 fw-bold">Top most liked post</span></p>
                                    <p><?php echo $title;?></p>
                                    <img class="rounded" src="../assets/images_post/<?php echo $image;?>" alt="">
                                    <p class="mt-3"><?php echo $desciption.'...';?></p>
                                    <p class="post-creator-info">Category: <span class="fw-bold"><?php echo $category;?></span></p>
                                    <p class="post-creator-info">Posted by: <a href="user_details.php?user_id=<?php echo $user_id;?>" class="text text-primary"><?php echo $user_post;?></a>, <?php echo $date_create;?></p>
                                </div>

                                <?php
                            }else{
                                ?>
                                <p class="bg-white">There is not post liked for now!</p>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/main.js"></script>
</body>
</html>