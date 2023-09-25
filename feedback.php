
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
<div class="container FEEDBACK">
       <div class="container">
            <div class="row title-area mt-3 mb-2">
                <h3 class="d-flex align-items-center"><a class="nav-link pe-2" href="profile.php">Profile</a> > <a class="nav-link px-2" href="user_settings.php">Settings</a> > <a class="nav-link px-2" href="feedback.php">Feedback</a></h3>
            </div> 
            <div class="row ps-2">
                <div class="row mb-2">
                    <p class="p-0 m-0">Create feedback</p>
                </div>
                <div class="row">
                    <a href='create_feedback.php' class='add-feedback btn'><i class="fa-solid fa-plus"></i></a>
                </div>
                
                <div class="row mt-3">
                    <p class="p-0 m-0">Feedback history</p>
                </div>
                <div class="main-row p-0 m-0">
                <?php 
                if(isset($_SESSION['id'])){
                    $id=$_SESSION['id'];
                    $username=getUsername($connection,$id);

                    function getReplay($conn,$id){
                        $sql="SELECT * FROM feedbacks_replay WHERE feedback_replayed_on_id='$id'";
                        $statement=$conn->query($sql);
                        if(!$statement){
                            die('invalid query!').$conn->getMessage();
                        }
                        while($res=$statement->fetch()){
                            return $res;
                        }
                    }

                    $sql="SELECT COUNT(*) AS isFeedbackExist FROM feedbacks WHERE feedback_owner='$username'";
                    $statement=$connection->query($sql);
                    if(!$statement){
                        die('invalid query!').$connection->getMessage();
                    }
                    while($res=$statement->fetch()){
                        $isFeedbackExist=$res['isFeedbackExist'];
                        if($isFeedbackExist>0){
                            // echo "ok";
                            $sql="SELECT * FROM feedbacks WHERE  feedback_owner='$username'";
                            $statement=$connection->query($sql);
                            if(!$statement){
                                die('invalid query!').$connection->getMessage();
                            }
                            while($res=$statement->fetch()){
                                $id=$res['id'];
                                $replayed=$res['replayed'];
                                $feedback_owner=ucfirst($res['feedback_owner']);
                                $feedback_owner_img=$res['feedback_owner_img'];
                                $feedback_owner_title=$res['feedback_owner_title'];
                                $feedback_date=strftime("%b %d, %Y", strtotime($res['feedback_date']));
                                $title=ucfirst($res['title']);
                                $message=$res['message'];
                                $image=setDefaultImage($feedback_owner_img,$default);
                            ?>
                            <div class="row mb-2 m-0 p-0">
                                <div class="col-12 col-md-4 col-lg-3 main-feed rounded mt-3">
                                    <?php
                                    if($replayed==='false'){
                                        ?>
                                        <div class="col-12 col-md-2 col-lg-2 py-2 d-flex feed-state">
                                            <p class="sent text text-white px-2 me-1 rounded">sent</p>
                                            <p class="replay text text-white px-2 rounded">replayed</p>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-12 col-md-2 col-lg-2 py-2 d-flex feed-state replayed">
                                            <p class="sent text text-white px-2 me-1 rounded">sent</p>
                                            <p class="replay text text-white px-2 rounded">replayed</p>
                                        </div>
                                        <?php
                                    }
                                    
                                    ?>
                                    <div class="col-12 col-md-12 col-lg-12 feedback-user-info d-flex align-items-start px-2 rounded">
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
                                    <?php
                                    
                                    $res=getReplay($connection,$id);
                                    if($res!=null){
                                        // echo "ok";
                                        $replay_message=$res['replay_message'];
                                        $replay_owner=$res['replay_owner'];
                                        $replay_date=strftime("%b %d, %Y", strtotime($res['replay_date']));
                                        ?>
                                        <div class="replay-area px-2">
                                            <p class="p-0 m-0">Replay by_<span class="text text-warning p-0 m-0"><?php echo $replay_owner;?></span></p>
                                            <div class="replay-feeback-message">
                                                <p class="p-0 m-0"><?php echo $replay_message;?></p>
                                                <p class="p-0 m-0 date"><?php echo $replay_date;?></p>
                                            </div>
                                        </div>
                                        <?php
                                    }else{
                                        // echo "err";
                                    }
                                    ?>
                                    
                                </div>
                            </div>
                            <?php
                            }
                        }else{
                            // echo "err";
                            ?>
                            <p class="text text-dark p-0 m-0 mt-3">You don't have any feedbacks yet !</p>
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
    
</body>
</html>


                   