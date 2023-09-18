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
    <div class="container NOTIFICATIONS">
        <div class="container">
            <div class="row title-area mt-3 mb-2">
                <h3>Notifications</h3>
            </div>
            <?php
            if(isset($_SESSION['id'])){
              $id=$_SESSION['id'];
              $username=getUsername($connection,$id);

              // fetch notification details
              $sql="SELECT * FROM notifications WHERE noti_receiver='$username'";
              $statement=$connection->query($sql);
              if(!$statement){
                die('invalid query!').$connection->getMessage();
              }
              while($res=$statement->fetch()){
                $noti_id=$res['id'];
                $post_id=$res['post_reacted_id'];
                $post_title=substr($res['post_reacted_title'],0,35);
                $noti_content=ucfirst($res['content']);
                $noti_date=strftime("%b %d, %Y", strtotime($res['date_noti']));
                $noti_sender_img=$res['noti_sender_img'];
                $noti_status=$res['readed'];

                $default="assets/images/default_user.webp";
                if($noti_sender_img !=null){
                    $image="assets/images/$noti_sender_img";
                    $user_send_noti=$image;
                }else{
                    $user_send_noti=$default;
                }

                if($noti_status==='false'){
                  // echo 'not read';
                  ?>
                  <div class="row">
                      <div class="col-12 col-md-4 col-lg-3">
                          <a  href='notifications_readed.php?noti_id=<?php echo $noti_id;?>&post_id=<?php echo $post_id;?>' class='noti-area not-readed nav-link mt-1 py-1 px-1 rounded'>
                            <div class='row'>
                              <div class='col-md-4 col-lg-12 d-flex justify-content-start align-items-start'>
                                <div class='left'>
                                  <img class='rounded-circle' src='<?php echo $user_send_noti;?>' alt=''>
                                </div>
                                <div class='right ms-2'>
                                  <div class="header-area d-flex justify-content-between p-0 m-0">
                                    <span class=''><?php echo $noti_content;?></span>
                                    <?php
                                    if(str_contains($noti_content, 'like')){
                                      ?>
                                      <span><i class="fa-solid fs-6 fa-thumbs-up"></i></span>
                                      <?php
                                    }elseif(str_contains($noti_content, 'comment')){
                                      ?>
                                      <span><i class="fa-solid fs-6 fa-comment"></i></span>
                                      <?php
                                    }else{
                                      echo "err";
                                    }
                                    ?>
                                  </div>
                                  <span class='post-title-area'><?php echo $post_title." ...";?></span><br>
                                  <span class='post-date-area'><?php echo $noti_date;?></span>
                                </div>
                              </div>
                            </div>
                          </a>
                      </div>
                  </div>
                  <?php
                }else{
                  // echo 'read';
                  ?>
                  <div class="row">
                      <div class="col-12 col-md-4 col-lg-3">
                          <a  href='notifications_readed.php?noti_id=<?php echo $noti_id;?>&post_id=<?php echo $post_id;?>' class='noti-area readed nav-link mt-1 py-1 px-1 rounded'>
                            <div class='row'>
                              <div class='col-md-4 col-lg-12 d-flex justify-content-start align-items-start'>
                                <div class='left'>
                                  <img class='rounded-circle' src='<?php echo $user_send_noti;?>' alt=''>
                                </div>
                                <div class='right ms-2'>
                                  <div class="header-area d-flex justify-content-between p-0 m-0">
                                    <span class=''><?php echo $noti_content;?></span>
                                    <!-- chekc if event noti_content is like or comment -->
                                    <?php
                                    if(str_contains($noti_content, 'like')){
                                      ?>
                                      <span><i class="fa-solid fs-6 fa-thumbs-up"></i></span>
                                      <?php
                                    }elseif(str_contains($noti_content, 'comment')){
                                      ?>
                                      <span><i class="fa-solid fs-6 fa-comment"></i></span>
                                      <?php
                                    }else{
                                      echo "err";
                                    }
                                    ?>
                                  </div>
                                  <span class='post-title-area'><?php echo $post_title." ...";?></span><br>
                                  <span class='post-date-area'><?php echo $noti_date;?></span>
                                </div>
                              </div>
                            </div>
                          </a>
                      </div>
                  </div>
                  <?php
                }
              }
            }
            
            ?>
            
        </div>
    </div>
    
</body>
</html>