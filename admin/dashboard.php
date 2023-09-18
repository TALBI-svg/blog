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
            <div class="col-12 col-md-2 col-lg-2">
              <nav>
                <div class="nav nav-tabs border-light" id="nav-tab" role="tablist">
                  <button class="nav-link text text-start w-100 mb-2 active" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users" type="button" role="tab" aria-controls="nav-users" aria-selected="true">Users</button>
                  <button class="nav-link text text-start w-100 mb-2" id="nav-posts-tab" data-bs-toggle="tab" data-bs-target="#nav-posts" type="button" role="tab" aria-controls="nav-posts" aria-selected="false">Posts</button>
                  <button class="nav-link text text-start w-100 mb-2" id="nav-statics-tab" data-bs-toggle="tab" data-bs-target="#nav-statics" type="button" role="tab" aria-controls="nav-statics" aria-selected="false">Statics</button>
                </div>
              </nav>
            </div>

            <div class="col-12 col-md-10 col-lg-10 content-area">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab" tabindex="0">
                    
                    <div class="row d-flex justify-content-end">
                        <div class="col-12 col-md-4 col-lg-3">
                            <form action="" method="POST" autocomplete="off" id="sendRequestForm">
                              <div class="mb-3 d-flex">
                                  <input type="text" name="search" value="" id="whenSearchInpt" class="form-control" placeholder="search user">
                                  <button type="button" name="searchUserBtn" class="btn btn-dark ms-2">Search</button>
                              </div>
                            </form>
                        </div>
                    </div>
                    <?php?>

                    <div class="table-responsive hideTable">
                        <table class="table border shadow-sm">
                            <thead>
                                <tr class="">
                                    <th>id</th>
                                    <th>username</th>
                                    <th>profile</th>
                                    <th>email</th>
                                    <th>joined_date</th>
                                    <th>address</th>
                                    <th>status</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(isset($_SESSION['admin_id'])){
                                    $admin_id=$_SESSION['admin_id'];
                                    $username_admin=getAdminUsername($connection,$admin_id);
                                
                                    $sql="SELECT * FROM users";
                                    $statement=$connection->query($sql);
                                    if(!$statement){
                                        die('invalid query!').$connection->getMessage();
                                    }
                                    while($res=$statement->fetch()){
                                        $user_id=$res['id'];
                                        $user_username=$res['username'];
                                        $user_email=$res['email'];
                                        $user_joined_date=strftime("%b %d, %Y", strtotime($res['created_at']));
                                        $user_address=$res['address'];
                                        $user_status=$res['status'];
                                        $user_profile_pic=$res['profile_pic'];

                                        $default="../assets/images/default_user.webp";
                                        if($user_profile_pic !=null){
                                            $user_image="../assets/images/$user_profile_pic";
                                            $profile_image=$user_image;
                                        }else{
                                            $profile_image=$default;
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $user_id;?></td>
                                            <td><?php echo $user_username;?></td>
                                            <td class="users-image"><img class="rounded-circle" src="<?php echo $profile_image;?>" alt=""></td> 
                                            <td><?php echo $user_email;?></td>
                                            <td><?php echo $user_joined_date;?></td>
                                            <td><?php echo $user_address;?></td> 
                                            <td><?php echo $user_status;?></td>
                                            <td class="">
                                                <?php
                                                if($user_status==='disactive'){
                                                    // echo "able";
                                                    ?>
                                                    <a href="disable_user.php?user_id=<?php echo $user_id;?>" class="btn btn-sm btn-success text text-start" style="width:60px;">active</a>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <a href="disable_user.php?user_id=<?php echo $user_id;?>" class="btn btn-sm btn-warning" style="width:60px;">disative</a>
                                                    <?php
                                                }
                                                ?>
                                                <a href="#" class="btn btn-sm btn-danger">delete</a>
                                                
                                                <a class='show-more-btn bg-primary btn btn-sm border-0' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis text text-white'></i></a>  
                                                <div class='dropdown'>
                                                  <ul class='dropdown-menu'>
                                                    <li><a class='dropdown-item' href='set_default_password.php?user_id=<?php echo $user_id;?>'>Default password</a></li>
                                                    <li><a class='dropdown-item' href='#'>Other options</a></li>
                                                  </ul>
                                                </div>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    header("location:../login.php");
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-posts" role="tabpanel" aria-labelledby="nav-posts-tab" tabindex="0">
                <p>posts</p>
                </div>
                <div class="tab-pane fade" id="nav-statics" role="tabpanel" aria-labelledby="nav-statics-tab" tabindex="0">
                <p>statics</p>
                </div>
              </div>
            </div>
        </div>
        

        
    </div>
    
    <script src="../assets/main.js"></script>
</body>
</html>