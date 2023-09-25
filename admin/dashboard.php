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
        <div class="row mt-3"> 
            <div class="col-6 col-md-2 col-lg-2">
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
                            <p><i class="fa-solid fa-users"></i><span class="ms-3">Users</span></p>
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
            <div class="col-6 col-md-2 col-lg-2">
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
                            <p><i class="fa-solid fa-newspaper"></i><span class="ms-3">Posts</span></p>
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
            <div class="col-6 col-md-2 col-lg-2">
                <div class="card-container border rounded p-2 bg-info text text-white shadow-md">
                    <p><i class="fa-solid fa-people-roof"></i><span class="ms-3">Groups</span></p>
                    <p>2</p>
                </div>
            </div>
            <div class="col-6 col-md-2 col-lg-2">
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
                            <p><i class="fa-solid fa-comment-dots"></i><span class="ms-3">Feedbacks</span></p>
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
            <div class="col-12 col-md-4 col-lg-4">
                <?php
                if(isset($_SESSION['admin_id'])){
                    $admin_id=$_SESSION['admin_id'];
                    $username_admin=getAdminUsername($connection,$admin_id);

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
                }
                ?>
                <div class="card-container border rounded p-2 bg-success text text-white shadow-md">
                    <p><i class="fa-solid fa-chart-line"></i><span class="ms-3">Top must liked posts</span></p>
                    <p>Content</p>
                    <p><?php echo $postML_id;?></p>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-8">
                <div class="post-category-counting mt-4">
                    <p class="fw-bold">Count posts for each category</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/main.js"></script>
</body>
</html>