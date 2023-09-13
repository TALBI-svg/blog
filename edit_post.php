<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if((isset($_SESSION['id']) || isset($_GET['post_id'])) && !isset($_POST['updateBtn'])){
    // get the user name 
    $id=$_SESSION['id'];
    $sql="SELECT * FROM users WHERE id = :id";
    $statement=$connection->prepare($sql);
    $statement->execute(array(':id'=>$id));
    while($res= $statement->fetch()){
      $username=$res['username'];
    }
    
    // get all post_info from db
    if(isset($_GET['post_id'])){
        $post_id=$_GET['post_id'];
        $sql="SELECT * FROM posts WHERE id=:id";
        $statement=$connection->prepare($sql);
        $statement->execute(array(':id'=>$post_id));
        while($res=$statement->fetch()){
            $title=$res['title'];
            $description=$res['description'];
            $image_post_url="assets/images_post/$res[image]";
            $category=$res['category'];
            
        }
    }
}elseif(isset($_POST['updateBtn'])){
    $form_errors=array();
    $require_fileds=array("title","description");
    $form_errors=array_merge($form_errors,check_empty_fields($require_fileds));

    $fields_to_check_length=array("title"=>5, "description"=>5);
    $form_errors=array_merge($form_errors, check_min_length($fields_to_check_length));
    // $fields_to_check_max_length=array("title"=>100, "description"=>500);
    // $form_errors=array_merge($form_errors, check_max_length(($fields_to_check_max_length)));

    if(empty($form_errors)){
        $post_id=$_POST['post_id'];
        $title=strip_tags($_POST['title']);
        $description=strip_tags(trim($_POST['description']));
        empty($_POST['category']) ? $category = 'front-end': $category = $_POST['category'];
          
        // Validate image post
        if($_FILES['image']['error']===4){
          $result=falshMessage("image dose not updated!");
        }else{
          $file_name=$_FILES['image']['name'];
          $file_size=$_FILES['image']['size'];
          $file_tmp_name=$_FILES['image']['tmp_name'];

          $validImageExtension=['jpg','png','webp','gif','bmp'];
          $imageExtension=explode('.', $file_name);
          $imageExtension=strtolower(end($imageExtension));

          if(!in_array($imageExtension, $validImageExtension)){
            $result=falshMessage("invalid image extension!");
          }elseif($file_size>1000000){
            $result=falshMessage("image size to much large!");
          }else{

            $image_post=uniqid();
            $image_post.=".".$imageExtension;
            move_uploaded_file($file_tmp_name,"assets/images_post/".$image_post);

            try{
                $sql="UPDATE posts SET title =:title, image =:image, description =:description, category =:category, date_create=now(), user_post =:user_post WHERE id =:post_id";
                $statement=$connection->prepare($sql);
                $statement->execute(array(':title'=>$title, ':image'=>$image_post, ':description'=>$description, ':category'=>$category, ':user_post'=>$username, ':post_id'=>$post_id));
                
                if($statement->rowCount()==1){
                  $result=falshMessage('Post updated successfully','Pass');
                
                }
            }catch(PDOException $ex){
                $result=falshMessage('Error Exception '.$ex->getMessage());
            }
          }
        }
    }else{
        if(count($form_errors)==1){
            $result=falshMessage('One error in the form');

        }else{
            $result=falshMessage('There were '.count($form_errors).' errors in the form');

        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container EDITPOST">
        <div class="row d-flex justify-content-between">
            <?php if(isset($_SESSION['username'])):?>
            <div class="col-12 col-md-4 col-lg-3 p-0">

                <div class="container">
                    <form action="" method="post" enctype="multipart/form-data" class="ms-2 mt-3">
                      <h2 class="mb-3 text text-warning">Update Post</h2>
                      <div class="mb-3">
                        <input type="text" name="post_id" hidden value="<?php echo $post_id;?>">
                      </div>
                      <div class="mb-3">
                        <input type="text" name="title" value="<?php echo $title;?>" class="form-control" placeholder="Post title">
                      </div>
                      <div class="mb-3">
                        <textarea class="form-control" name="description" placeholder="Post description"  style="height: 200px">
                          <?php echo $description;?>
                        </textarea>
                      </div> 
                      <div class="mb-3 border border-sencondary p-3 rounded img-area" style="background-image:url(<?php  echo $image_post_url;?>);">
                        <label for="post-image"><i class="fa-solid fa-image fs-5"></i></label>
                        <input type="file" name="image" accept=".jpg, .png, .webp, .gif, .bmp" hidden  id="post-image" style="background-size:cover;">
                        <script>
                            let image_display=document.querySelector('.img-area')
                            let input_image=document.querySelector('#post-image')
                            uploaded_image="";
                            input_image.addEventListener('change',function(){
                                const reader= new FileReader();
                                reader.addEventListener('load',()=>{
                                    uploaded_image=reader.result;
                                    image_display.style.backgroundImage=`url(${uploaded_image})`;
                                })
                                reader.readAsDataURL(this.files[0]);
                            })
                        </script> 
                      </div>
                      <select name="category" class="form-select mb-3" aria-label="Default select example">
                        <option selected disabled>Category</option>
                        <option value="front-end">Front-end</option>
                        <option value="back-end">Back-end</option>
                        </select>
                      <div class="row">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" name="updateBtn" class="signupBtn btn btn-warning">Update</button>
                        </div>
                      </div>
                    </form>

                    <?php if(isset($result)) echo $result;?>
                    <?php if(isset($form_errors)) echo show_errors($form_errors);?>
                </div>
                

            </div>
            <?php else:?>
            <div class="row d-flex justify-content-center align-items-center" style="height:80vh;">
                <div class="col-12 col-md-4 col-lg-3">
                    <h1 class="fw-bold"><span class="fs-2">You are not allowed to view this page ! <i class="fa-solid fa-ban fs-2"></i></span></h1>
                </div>
            </div>
            <?php endif?>
        </div>
    </div>
</body>
</html>