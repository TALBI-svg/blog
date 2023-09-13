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
    <div class="container FAVORITESPOSTSshow">
       <div class="container">
            <div class="row title-area mt-3 mb-2">
                <h3>Favorites Posts</h3>
            </div>
                <?php
                if(isset($_SESSION['id'])){
                    $id=$_SESSION['id'];
                    // Get "$username" from db
                    $username=getUsername($connection,$id);

                    $sql="SELECT * FROM favorites WHERE favorites_owner='$username' order by fave_date DESC";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die("invalid query".$connection->getMessage());
                    }
                    while($res=$statement->fetch()){
                        $title=substr($res['post_title'],0,35);
                        $description=substr($res['post_descripe'],0,50);
                        $image=$res['post_img'];
                        $post_id=$res['favorit_post_id'];
                        ?>
                        <div class="row">
                            <div class="col-12 col-md-7 col-lg-4 mb-2">
                                <div class="favorite-content-area d-flex rounded ">
                                    <a href="unfavorite_post.php?post_id=<?php echo $post_id;?>" class="btn-area d-flex justify-content-center align-items-center"><i class='fa-solid fa-trash fs-6 p-2 text text-danger favorit-post'></i></a>
                                    <img class="img-area" src="assets/images_post/<?php echo $image;?>" alt="">
                                    <div class="detailas-area ps-2">
                                        <span class="title-post-content fw-bold"><?php echo $title." ...";?></span><br>
                                        <span  class="descrip-post-content"><?php echo $description."...";?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                }
                
                ?>
       </div>
    </div>
    
</body>
</html>