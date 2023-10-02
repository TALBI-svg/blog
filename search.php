<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';

if(isset($_SESSION['id'])){
  $id=$_SESSION['id'];
  $username=getUsername($connection,$id);

  if(isset($_POST['searchBtn']) and !empty($_POST['search'])){
    $search_content=$_POST['search'];
    $search_owner=$username;
    try{
      $sql="INSERT INTO search_history (search_content,search_owner) 
      VALUES (:search_content,:search_owner)";
      $statement=$connection->prepare($sql);
      $statement->execute(array(':search_content'=>$search_content,':search_owner'=>$search_owner));

    }catch(PDOException $ex){
        die("Error Exception".$ex->getMessage());
    }
  }
}else{
  header("location: login.php");
}
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
    <div class="container SEARCH">
        <div class="col-12 col-md-4 col-lg-4 mt-5">
          <div class="container search-area-body">
            <form action="" method="POST" autocomplete="off">
              <div class="mb-3 d-flex">
                  <input type="text" name="search" value="" class="form-control" placeholder="Search post">
                  <button name="searchBtn" class="btn btn-dark ms-2">Search</button>
              </div>
            </form>
            <div class="search-result-area">

            <?php 
            if(isset($_POST['searchBtn']) and !empty($_POST['search'])){
              // echo "result"; 

              if(isset($_SESSION['id'])){
                $id=$_SESSION['id'];
                $username=getUsername($connection,$id);
                if(isset($_POST['searchBtn']) and !empty($_POST['search'])){
                  $search=$_POST['search'];
                  $sql="SELECT * FROM posts WHERE title like '%$search%' or description like '%$search%'";
                  $statement=$connection->prepare($sql);
                  $statement->execute();
                  if(!$statement){
                      $result=falshMessage('invalid query!'.$connection->getMessage());
                  }
                  while($res=$statement->fetch()){
                    $image=$res['image'];
                    $title=$res['title'];
                    $description=substr($res['description'],0,45);
                    $date_create=strftime("%b %d, %Y", strtotime($res['date_create']));
                    $post_id=$res['id'];
                    ?>
                    <a href='post_details.php?post_id=<?php echo $post_id;?>' class='text text-dark show-search-area'>
                      <div class='show-content-area'>
                        <div class='main-area col-12 col-md-12 col-lg-12 d-flex p-2 mb-2 rounded'>
                          <img src='assets/images_post/<?php echo $image;?>' class='img-fluid' alt=''>
                          <div class='row ms-1'>
                            <div class='col-12 col-md-12 col-lg-12 ps-1'>
                              <p class='p-0 m-0'><?php echo $title;?></p>
                              <p class='p-0 m-0'><?php echo $description;?></p>
                              <p class='p-0 m-0 date-time'><?php echo $date_create;?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                    <?php
                  }
                }
              }else{
                header("location: login.php");
              }
               

            }else{
              // echo "history";
              if(isset($_SESSION['id'])){
                $id=$_SESSION['id'];
                $username=getUsername($connection,$id);
  
                $sql="SELECT DISTINCT search_content,id FROM search_history WHERE search_owner='$username'";
                $statement=$connection->query($sql);
                if(!$statement){
                    die("invalid query!").$connection->getMessage();
                }
                ?>
                <h5>Search History</h5>
                <?php
                while($res=$statement->fetch()){
                  $search_content=$res['search_content'];
                  $search_content_id=$res['id'];
                  ?>
                  <!-- <a href='#' class='text text-dark show-search-area'> -->
                    <div class='show-content-area'>
                      <div class='main-area col-12 col-md-12 col-lg-12 d-flex p-2 ps-0 mb-2'>
                          <div class='col-12 col-md-12 col-lg-12 d-flex justify-content-between align-items-center'>
                            <p class='p-0 m-0'><?php echo $search_content;?></p>
                            <a href="delete_search_history.php?search_id=<?php echo $search_content_id;?>" class="remove-result"><i class='fa-solid fa-x'></i></a>
                          </div>
                      </div>
                    </div>
                  <!-- </a> -->
                  
                  <?php
                }
              }else{
                header("location: login.php");
              }
            }
            ?>
            
            </div>       
          </div>
        </div>
    </div>
    
</body>
</html>