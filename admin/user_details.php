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
    <div class="container p-3 px-4 mt-0 UDERDETAILS"> 
        <div class="row">
            <?php
            if(isset($_SESSION['admin_id'])){
                if($_SERVER['REQUEST_METHOD']==='GET'){

                    $user_id=$_GET['user_id'];
                    if($user_id !=null){
                        // check if user_id exist in database?
                        $sql="SELECT COUNT(*) AS isIdExist FROM users WHERE id='$user_id'";
                        $statement=$connection->query($sql);
                        if(!$statement){
                            die("invalid query!").$connection->getMessage();
                        }
                        while($res=$statement->fetch()){
                            $isIdExist=$res['isIdExist'];

                            if($isIdExist>0){
                                // echo "id exist";

                                // get user info
                                function getUserInfo($conn,$user_id){
                                    $sql="SELECT * FROM users WHERE id=$user_id";
                                    $statement=$conn->query($sql);
                                    if(!$statement){
                                        die("invalid query!").$conn->getMessage();
                                    }
                                    while($res=$statement->fetch()){
                                        return $res;
                                    }
                                }
                                $res=getUserInfo($connection,$user_id);
                                $username=ucfirst($res['username']);
                                $user_title=$res['title'];
                                $default="../assets/images/default_user.webp";
                                $user_profile=$res['profile_pic'];
                                if($user_profile !=null){
                                    $origine_image="../assets/images/$user_profile";
                                    $image=$origine_image;
                                }else{
                                    $image=$default;
                                }
                            
                                // get users's posts info
                                function getUserPostsCount($conn,$user_name){
                                    $sql="SELECT COUNT(*) AS postsCount FROM posts WHERE user_post='$user_name'";
                                    $statement=$conn->query($sql);
                                    if(!$statement){
                                        die("invalid query!").$conn->getMessage();
                                    }
                                    while($res=$statement->fetch()){
                                        return $res;
                                    }
                                }
                                $res=getUserPostsCount($connection,$username);
                                $postsCount=$res['postsCount'];

                                // get users's posts categories info
                                function getCategories($conn,$user_name){
                                    $sql="SELECT DISTINCT category AS postsCategories FROM posts WHERE user_post='$user_name'";
                                    $statement=$conn->query($sql);
                                    if(!$statement){
                                        die("invalid query!").$conn->getMessage();
                                    }
                                    while($resCate=$statement->fetch()){
                                        $postsCategories=$resCate['postsCategories'];
                                        ?>
                                        <span class="ms-1 p-1 py-0 categories-area"><?php echo $postsCategories;?></span>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class='card mb-3 border-0'>
                                      <div class='user-img-area p-3'>
                                          <img src='<?php echo $image;?>' class='rounded-circle' alt='...'>
                                      </div>

                                      <div class='card-body p-0 mt-2'>
                                        <p class='card-title p-0 m-0 mb-1'>Member Name : <?php echo $username;?></p>
                                        <p class='card-text p-0 m-0 mb-1'>Member Title : <?php echo $user_title;?></p>
                                        <p class='card-text p-0 m-0 mb-1'>Posts Number : <?php echo $postsCount;?></p>
                                        <p class='card-text p-0 m-0 mb-1'>Posts Categories : <?php getCategories($connection,$username);?></p>
                                      </div>
                                    </div>
                                </div>
                                <?php
                            }else{
                                ?>
                                <p class="fw-normal">Invalid user_id plz try again!</p>
                                <?php
                            }
                            
                        }
                    }else{
                        ?>
                        <p class="fw-normal">Invalid or Null user_id plz try again!</p>
                        <?php
                    }
                }
            }else{
                header("location: ../login.php");
            }
            ?>
           
        </div>
    </div>
</body>
</html>