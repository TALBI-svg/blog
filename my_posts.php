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
    <link rel="stylesheet" href="assets/style_profile.css">
</head>
<body>
    <div class="container  p-3 px-4 mt-5 MYPOSTS">
        <div class='row'>
        <?php
        if(isset($_SESSION['id'])){
            $id=$_SESSION['id'];
            $username=getUsername($connection,$id);


            $sql="SELECT * FROM posts WHERE user_post='$username'";
            $statement=$connection->query($sql);
            if(!$statement){
                die("invalid query!".$connection->error);
            }
            while($res=$statement->fetch()){
                // $image_post=$res['image'];
                $bg_image_post_url="assets/images_post/$res[image]";
                $title=substr($res['title'],0,29);
                $description=substr($res['description'],0,32);
                echo"
                <div class='col-12 col-md-3 col-lg-2 me-5 mb-3'>
                    <div class='card'>
                      <div class='delete-area'>
                        <a href='delete_post.php?post_id=$res[id]' class='deleteBtn nav-link text text-end mt-1 me-2'><i class='fa-solid fa-trash'></i></a>
                        
                      </div>
                      <img src='assets/images_post/$res[image]' class='card-img-top' alt='...'>
                      <div class='card-body'>
                        <div class='info-area'>
                            <p class='card-text title-card-area'>$title</p>
                            <p class='card-text content-card-area'>$description</p>
                        </div>
                        <div class='hovered-area'>
                            <a href='edit_post.php?post_id=$res[id]' class='btn btn-warning py-1 signupBtn'>Edit Post</a>
                        </div>
                      </div>
                    </div>
                </div>
                ";
            }  

        }else{
            redirect("login");
        }

        ?>
        </div>  
    </div>
    <script src="assets/main.js"></script>
</body>
</html>