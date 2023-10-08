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
    <div class="container p-3 px-4 mt-0 PANELOPTIONS">
        <div class="row mt-3"> 
            
            <div class="col-12 col-md-2 col-lg-2">
              <nav>
                <div class="nav nav-tabs border-light" id="nav-tab" role="tablist">
                  <button class="nav-link text text-start w-100 mb-2" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users" type="button" role="tab" aria-controls="nav-users" aria-selected="false">Users</button>
                  <button class="nav-link text text-start w-100 mb-2" id="nav-groups-tab" data-bs-toggle="tab" data-bs-target="#nav-groups" type="button" role="tab" aria-controls="nav-groups" aria-selected="false">Groups</button>
                  <button class="nav-link text text-start w-100 mb-2 active" id="nav-posts-tab" data-bs-toggle="tab" data-bs-target="#nav-posts" type="button" role="tab" aria-controls="nav-posts" aria-selected="true">Posts</button>
                  <?php
                    $sql="SELECT COUNT(*) as isReplayed FROM feedbacks WHERE replayed='false'";
                    $statement=$connection->query($sql);
                    if(!$statement){
                      die("invalid query!").$connection->getMessage();
                    }
                    while($res=$statement->fetch()){
                      $isReplayed=$res['isReplayed'];
                    }
                  ?>
                  <button class="nav-link text text-start w-100 mb-2" id="nav-feedbacks-tab" data-bs-toggle="tab" data-bs-target="#nav-feedbacks" type="button" role="tab" aria-controls="nav-feedbacks" aria-selected="false">Feedbacks <span>(<?php echo $isReplayed;?>)</span></button>
                </div>
              </nav>
            </div>

            <div class="col-12 col-md-10 col-lg-10 content-area">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab" tabindex="0">
                    <div class="table-responsive hideTable">
                        <table class="table border">
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

                                        if($user_address==null){
                                            $address="<span class='text text-danger'>Address not added yet!</span>";
                                        }else{
                                            $address=$user_address;
                                        }
                                        
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
                                            <td class="text text-nowrap"><?php echo $address;?></td> 
                                            <td><?php echo $user_status;?></td>
                                            <td class="text text-nowrap">
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
                                                <a href="delete_user.php?user_id=<?php echo $user_id;?>" class="btn btn-sm btn-danger" id="deleteUserBtn">delete</a>
                                                
                                                <a class='show-more-btn bg-info btn btn-sm border-0' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis text text-white'></i></a>  
                                                <div class='dropdown'>
                                                  <ul class='dropdown-menu'>
                                                    <li><a class='dropdown-item' href='set_default_password.php?user_id=<?php echo $user_id;?>' id="defaultPasswordBtn">Default password</a></li>
                                                    <li><a class='dropdown-item' href='user_details.php?user_id=<?php echo $user_id;?>'>User details</a></li>
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
                <div class="tab-pane fade" id="nav-groups" role="tabpanel" aria-labelledby="nav-groups-tab" tabindex="0">
                <p>Groups</p>
                </div>

                <div class="tab-pane fade posts-area show active" id="nav-posts" role="tabpanel" aria-labelledby="nav-posts-tab" tabindex="0">
                    <div class="row d-flex m-0 p-0">
                        
                    </div>
                </div>

                <div class="tab-pane fade feedback-area" id="nav-feedbacks" role="tabpanel" aria-labelledby="nav-feedbacks-tab" tabindex="0">
                    <?php 
                    if(isset($_SESSION['admin_id'])){
                        $id=$_SESSION['admin_id'];
                        $username=getAdminUsername($connection,$id);
                        $sql="SELECT COUNT(*) AS isFeedbackExist FROM feedbacks";
                        $statement=$connection->query($sql);
                        if(!$statement){
                            die('invalid query!').$connection->getMessage();
                        }
                        while($res=$statement->fetch()){
                            $isFeedbackExist=$res['isFeedbackExist'];
                            if($isFeedbackExist>0){
                                // echo "ok";
                                $sql="SELECT * FROM feedbacks";
                                $statement=$connection->query($sql);
                                if(!$statement){
                                    die('invalid query!').$connection->getMessage();
                                }
                                while($res=$statement->fetch()){
                                    $feedback_owner=ucfirst($res['feedback_owner']);
                                    $feedback_owner_img=$res['feedback_owner_img'];
                                    $feedback_owner_title=$res['feedback_owner_title'];
                                    $feedback_date=strftime("%b %d, %Y", strtotime($res['feedback_date']));
                                    $title=ucfirst($res['title']);
                                    $message=$res['message'];
                                    $feedback_id=$res['id'];
                                    $replayed=$res['replayed'];
                                    $default="../assets/images/default_user.webp";
                                    if($feedback_owner_img !=null){
                                        $origine_image="../assets/images/$feedback_owner_img";
                                        $image=$origine_image;
                                    }else{
                                        $image=$default;
                                    }
                                    if($replayed==='false'){
                                        ?>
                                        <div class="col-12 col-md-5 col-lg-4 main-feed rounded mb-2 pb-2">
                                            <div class="col-12 col-md-12 col-lg-12 feedback-user-info d-flex align-items-start px-2 pt-3 rounded">
                                                <img class="mt-1 rounded-circle" src="<?php echo $image;?>" alt="">
                                                <div class="right p-0 ps-2">
                                                    <span><?php echo $feedback_owner;?></span><br>
                                                    <span class="title"><?php echo $feedback_owner_title;?></span>
                                                </div>
                                            </div>
                                            <div class="feeback-title px-2 mt-2">
                                                <p class="m-0 p-0"><?php echo $title;?>, <span class="date"><?php echo $feedback_date;?></span></p>
                                            </div>
                                            <div class="feeback-message px-2">
                                                <p><?php echo $message;?></p>
                                            </div>
                                            <div class="replay-area px-2">
                                                <a href="feedback_replay.php?feedback_id=<?php echo $feedback_id;?>">replay</a>
                                            </div>
                                        </div>                            
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-12 col-md-5 col-lg-4 main-feed replayed rounded mb-2 pb-2">
                                            <div class="col-12 col-md-12 col-lg-12 feedback-user-info d-flex align-items-start px-2 pt-3 rounded">
                                                <img class="mt-1 rounded-circle" src="<?php echo $image;?>" alt="">
                                                <div class="right p-0 ps-2">
                                                    <span><?php echo $feedback_owner;?></span><br>
                                                    <span class="title"><?php echo $feedback_owner_title;?></span>
                                                </div>
                                            </div>
                                            <div class="feeback-title px-2 mt-2">
                                                <p class="m-0 p-0"><?php echo $title;?>, <span class="date"><?php echo $feedback_date;?></span></p>
                                            </div>
                                            <div class="feeback-message px-2">
                                                <p><?php echo $message;?></p>
                                            </div>
                                            <div class="replay-area px-2">
                                                <a  href="feedback_replay.php?feedback_id=<?php echo $feedback_id;?>">replayed</a>
                                            </div>
                                        </div>                            
                                        <?php
                                    }
                                }
                            }else{
                                // echo "err";
                                ?>
                                <p class="text text-dark mt-3">You don't have any feedbacks yet !</p>
                                <?php
                            }
                        }
                    }else{
                        redirect('login');
                    }
                    ?>
                </div>
              </div>
            </div>

        </div>
    </div>
    
    <script src="../assets/main.js"></script>
</body>
</html>