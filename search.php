<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';


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
                  echo"
                  <a href='post_details.php?post_id=$res[id]' class='text text-dark show-search-area'>
                  <div class='show-content-area'>
                    <div class='main-area col-12 col-md-12 col-lg-12 d-flex p-2 mb-2 rounded'>
                      <img src='assets/images_post/$image' class='img-fluid' alt=''>
                      <div class='row ms-1'>
                        <div class='col-12 col-md-12 col-lg-12 ps-1'>
                          <p class='p-0 m-0'>$title</p>
                          <p class='p-0 m-0'>$description</p>
                          <p class='p-0 m-0 date-time'>$date_create</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  </a>
                  ";
                }
                
              }

            ?> 
            </div>       
          </div>
        </div>
    </div>
    
</body>
</html>